from flask import request
from flask_restx import Resource, Namespace, fields
from flask_jwt_extended import create_access_token
from werkzeug.security import check_password_hash

from ..models.user import User
from ..extensions import api

ns = Namespace('auth', description='Authentification et autorisation')

@ns.route('/login')
class Login(Resource):
    @ns.doc('login')
    @ns.expect(api.model('Login', {
        'email': fields.String(required=True, description='Email de l\'utilisateur'),
        'password': fields.String(required=True, description='Mot de passe de l\'utilisateur')
    }))
    @ns.response(200, 'Succès', api.model('Token', {
        'access_token': fields.String(description='Token d\'accès JWT')
    }))
    @ns.response(401, 'Non autorisé')
    def post(self):
        """Authentifier un utilisateur et retourner un token JWT"""
        data = request.get_json()
        user = User.query.filter_by(email=data['email']).first()

        if user and check_password_hash(user.password, data['password']):
            access_token = create_access_token(
                identity=user.email,
                additional_claims={
                    'id': user.id,
                    'roles': user.roles
                })
            return {'access_token': access_token}, 200

        return {'message': 'Email ou mot de passe incorrect'}, 401