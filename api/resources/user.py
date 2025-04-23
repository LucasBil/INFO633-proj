from flask import request
from flask_restx import Resource, Namespace, fields
from flask_jwt_extended import jwt_required, get_jwt

from ..services.user_service import UserService
from ..models.user import User
from ..extensions import api

ns = Namespace('user', description='Opérations sur les utilisateurs')

@ns.route('/')
class UserList(Resource):
    @ns.expect(api.model('UserCreate', {
        'email': fields.String(required=True, description='Email de l\'utilisateur'),
        'password': fields.String(required=True, description='Mot de passe de l\'utilisateur'),
        'first_name': fields.String(description='Prénom de l\'utilisateur'),
        'last_name': fields.String(description='Nom de famille de l\'utilisateur')
    }))
    @ns.marshal_with(User.get_model(), code=201)
    @ns.response(201, 'Utilisateur créé avec succès')
    @ns.response(400, 'Erreur de validation des données')
    def post(self):
        """Créer un nouvel utilisateur"""
        data = api.payload
        return UserService.create_user(
            email=data['email'],
            password=data['password'],
            first_name=data.get('first_name'),
            last_name=data.get('last_name')
        ), 201
    
    @ns.marshal_list_with(User.get_model())
    @ns.param('email', 'Filtrer par email')
    @ns.param('first_name', 'Filtrer par prénom')
    @ns.param('last_name', 'Filtrer par nom')
    @ns.param('roles', 'Filtrer par roles')
    @ns.response(200, 'Liste des utilisateurs')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self):
        """Récupérer la liste des utilisateurs avec/sans des filtres"""
        search_query = {
            'email': request.args.get('email'),
            'first_name': request.args.get('first_name'),
            'last_name': request.args.get('last_name'),
            'roles': request.args.get('roles'),
        }
        query = {k: v for k, v in search_query.items() if v}
        return UserService.get_all_users(query), 200
    
@ns.route('/<int:user_id>')
@ns.param('user_id', 'ID de l\'utilisateur')
class UserResource(Resource):
    @ns.marshal_with(User.get_model())
    @ns.response(200, 'Utilisateur trouvé')
    @ns.response(404, 'Utilisateur non trouvé')
    @jwt_required()
    def get(self, user_id):
        """Récupérer un utilisateur par son ID"""
        return UserService.get_user_by_id(user_id), 200
    
    @ns.expect(api.model('UserUpdate', {
        'email': fields.String(description='Email de l\'utilisateur'),
        'first_name': fields.String(description='Prénom de l\'utilisateur'),
        'last_name': fields.String(description='Nom de famille de l\'utilisateur')
    }))
    @ns.marshal_with(User.get_model())
    @ns.response(200, 'Utilisateur mis à jour avec succès')
    @ns.response(400, 'Erreur de validation des données')
    @ns.response(403, 'Accès interdit')
    @ns.response(404, 'Utilisateur non trouvé')
    @jwt_required()
    def put(self, user_id):
        """Mettre à jour un utilisateur par son ID"""
        data = api.payload
        if user_id != get_jwt()["id"]:
            return api.abort(403, 'Vous ne pouvez pas mettre à jour cet utilisateur')
        return UserService.update_user(
            user_id,
            email=data.get('email'),
            first_name=data.get('first_name'),
            last_name=data.get('last_name')
        ), 200
    
    @ns.marshal_with(User.get_model())
    @ns.response(200, 'Utilisateur supprimé avec succès')
    @ns.response(404, 'Utilisateur non trouvé')
    @ns.response(403, 'Accès interdit')
    @jwt_required()
    def delete(self, user_id):
        """Supprimer un utilisateur par son ID"""
        if user_id != get_jwt()["id"]:
            return api.abort(403, 'Vous ne pouvez pas supprimer cet utilisateur')
        return UserService.delete_user(user_id), 200