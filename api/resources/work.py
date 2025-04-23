from flask import request
from flask_restx import Resource, Namespace, fields
from flask_jwt_extended import jwt_required
from ..decorations.roles import roles_required

from ..services.work_service import WorkService
from ..models.work import Work
from ..extensions import api

ns = Namespace('works', description='Opérations sur les liaisons entre utilisateurs et projets')

@ns.route('/')
class WorkList(Resource):
    @ns.marshal_list_with(Work.get_model())
    @ns.param('id_user', 'Filtrer par ID d\'utilisateur')
    @ns.param('id_project', 'Filtrer par ID de projet')
    @ns.response(200, 'Liste des liaisons entre utilisateurs et projets')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self):
        """Récupérer la liste des projets"""
        search_query = {
            'id_user': request.args.get('id_user'),
            'id_project': request.args.get('id_project')
        }
        query = {k: v for k, v in search_query.items() if v}
        return WorkService.get_all_works(query), 200
    
    @ns.expect(api.model('WorkCreate', {
        'user_id': fields.Integer(required=True, description='ID de l\'utilisateur'),
        'project_id': fields.Integer(required=True, description='ID du projet')
    }))
    @ns.marshal_list_with(Work.get_model())
    @ns.response(201, 'Liaison entre utilisateur et projet créée')
    @ns.response(400, 'Erreur de validation des données')
    @ns.response(404, 'Utilisateur ou projet non trouvé')
    @ns.response(409, 'Conflit : la liaison existe déjà')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def post(self):
        """Créer une nouvelle liaison entre utilisateur et projet"""
        data = api.payload
        return WorkService.create_work(
            user_id=data['user_id'],
            project_id=data['project_id']
        ), 201
    
@ns.route('/<int:user_id>/<int:project_id>')
@ns.param('user_id', 'ID de l\'utilisateur')
@ns.param('project_id', 'ID du projet')
class WorkList(Resource):
    @ns.marshal_list_with(Work.get_model())
    @ns.response(200, 'Liaison entre utilisateur et projet supprimée')
    @ns.response(404, 'Liaison non trouvée')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def delete(self, user_id, project_id):
        """Supprimer une liaison entre utilisateur et projet"""
        return WorkService.delete_work(user_id, project_id), 200