from flask import request
from flask_restx import Resource, Namespace, fields
from flask_jwt_extended import jwt_required
from ..decorations.roles import roles_required

from ..services.admin_service import AdminService
from ..models.user import User
from ..extensions import api

ns = Namespace('admin', description='Opérations administrateurs')

@ns.route('/<int:user_id>')
@ns.param('user_id', 'ID de l\'utilisateur')
class AdminUser(Resource):
    @ns.expect(api.model('AdminUserUpdate', {
        'email': fields.String(description='Email de l\'utilisateur'),
        'password': fields.String(description='Mot de passe de l\'utilisateur'),
        'first_name': fields.String(description='Prénom de l\'utilisateur'),
        'last_name': fields.String(description='Nom de famille de l\'utilisateur'),
        'roles': fields.List(fields.String(enum=['admin', 'technician', 'teacher', 'student']), description='Liste des rôles de l\'utilisateur')
    }))
    @ns.marshal_with(User.get_model())
    @ns.response(200, 'Utilisateur mis à jour avec succès')
    @ns.response(400, 'Erreur de validation des données')
    @ns.response(404, 'Utilisateur non trouvé')
    @jwt_required()
    @roles_required('admin')
    def put(self, user_id):
        """Mettre à jour un utilisateur"""
        data = api.payload
        return AdminService.update_user(
            user_id,
            email=data.get('email'),
            password=data.get('password'),
            first_name=data.get('first_name'),
            last_name=data.get('last_name'),
            roles=data.get('roles')
        ), 200

    @ns.marshal_with(User.get_model())
    @ns.response(204, 'Utilisateur supprimé avec succès')
    @ns.response(404, 'Utilisateur non trouvé')
    @jwt_required()
    @roles_required('admin')
    def delete(self, user_id):
        """Supprimer un utilisateur"""
        return AdminService.delete_user(user_id), 204