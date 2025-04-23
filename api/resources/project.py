from flask import request
from flask_restx import Resource, Namespace, fields
from flask_jwt_extended import jwt_required, get_jwt
from ..decorations.roles import roles_required

from ..services.project_service import ProjectService
from ..models.project import Project
from ..extensions import api

ns = Namespace('project', description='Opérations sur les projets')

@ns.route('/')
class ProjectList(Resource):
    @ns.marshal_list_with(Project.get_model())
    @ns.param('name', 'Filtrer par nom')
    @ns.param('description', 'Filtrer par description')
    @ns.param('status', 'Filtrer par statut')
    @ns.param('year', 'Filtrer par année')
    @ns.param('id_creator', 'Filtrer par ID du créateur')
    @ns.response(200, 'Liste des projets')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self):
        """Récupérer la liste des projets"""
        search_query = {
            'name': request.args.get('name'),
            'description': request.args.get('description'),
            'status': request.args.get('status'),
            'year': request.args.get('year'),
            'id_creator': request.args.get('id_creator'),
        }
        query = {k: v for k, v in search_query.items() if v}
        return ProjectService.get_all_projects(query), 200
    
    @ns.marshal_list_with(Project.get_model())
    @ns.expect(api.model('ProjectCreate', {
        'name': fields.String(required=True, description='Nom du projet'),
        'description': fields.String(description='Description du projet'),
        'status': fields.String(required=True, description='Statut du projet', enum=['not_started', 'in_progress', 'completed', 'dismantled']),
        'year': fields.Integer(required=True, description='Année de début du projet'),
        'duration': fields.String(required=True, description='Durée du projet'),
        'id_creator': fields.Integer( description='ID de l\'utilisateur créateur du projet')
    }))
    @ns.response(201, 'Projet créé avec succès')
    @ns.response(400, 'Erreur de validation des données')
    @ns.response(403, 'Accès interdit')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def post(self):
        """Créer un nouveau projet"""
        data = api.payload
        return ProjectService.create_project(
            name=data['name'],
            description=data.get('description'),
            status=data['status'],
            year=data['year'],
            duration=data['duration'],
            id_creator=data['id_creator'] if 'id_creator' in data else get_jwt()["id"]
        ), 201
    
@ns.route('/<int:id_project>')
@ns.param('id_project', 'ID du projet')
class ProjectResource(Resource):
    @ns.marshal_with(Project.get_model())
    @ns.response(200, 'Projet récupéré avec succès')
    @ns.response(404, 'Projet non trouvé')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self, id_project):
        """Récupérer un projet par son ID"""
        return ProjectService.get_project_by_id(id_project), 200
    
    @ns.expect(api.model('ProjectUpdate', {
        'name': fields.String(description='Nom du projet'),
        'description': fields.String(description='Description du projet'),
        'status': fields.String(description='Statut du projet', enum=['not_started', 'in_progress', 'completed', 'dismantled']),
        'year': fields.Integer(description='Année de début du projet'),
        'duration': fields.String(description='Durée du projet')
    }))
    @ns.marshal_with(Project.get_model())
    @ns.response(200, 'Projet mis à jour avec succès')
    @ns.response(400, 'Erreur de validation des données')
    @ns.response(403, 'Accès interdit')
    @ns.response(404, 'Projet non trouvé')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def put(self, id_project):
        """Mettre à jour un projet par son ID"""
        data = api.payload
        return ProjectService.update_project(
            id_project,
            name=data.get('name'),
            description=data.get('description'),
            status=data.get('status'),
            year=data.get('year'),
            duration=data.get('duration')
        ), 200
    
    @ns.marshal_with(Project.get_model())
    @ns.response(200, 'Projet supprimé avec succès')
    @ns.response(404, 'Projet non trouvé')
    @ns.response(403, 'Accès interdit')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def delete(self, id_project):
        """Supprimer un projet par son ID"""
        return ProjectService.delete_project(id_project), 200