from flask import request
from flask_restx import Resource, Namespace, fields
from flask_jwt_extended import jwt_required, get_jwt
from ..decorations.roles import roles_required

from ..services.deliverable_service import DeliverableService
from ..models.deliverable import Deliverable
from ..extensions import api

ns = Namespace('deliverable', description='Opérations sur les livrables')

@ns.route('/')
class DeliverableList(Resource):
    @ns.marshal_list_with(Deliverable.get_model())
    @ns.param('name', 'Filtrer par nom')
    @ns.param('description', 'Filtrer par description')
    @ns.param('date_creation', 'Filtrer par date de création')
    @ns.param('date_closure', 'Filtrer par date de clôture')
    @ns.param('id_project', 'Filtrer par ID du projet')
    @ns.response(200, 'Liste des livrables')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self):
        """Récupérer la liste des livrables"""
        search_query = {
            'name': request.args.get('name'),
            'description': request.args.get('description'),
            'date_creation': request.args.get('date_creation'),
            'date_closure': request.args.get('date_closure'),
            'id_project': request.args.get('id_project'),
        }
        query = {k: v for k, v in search_query.items() if v}
        return DeliverableService.get_all_deliverables(query), 200
    
    @ns.marshal_list_with(Deliverable.get_model())
    @ns.expect(api.model('DeliverableCreate', {
        'name': fields.String(required=True, description='Nom du livrable'),
        'description': fields.String(description='Description du livrable'),
        'date_closure': fields.DateTime(description='Date de clôture du livrable'),
        'id_project': fields.Integer(required=True, description='ID du projet associé au livrable')
    }))
    @ns.response(201, 'Livrable créé avec succès')
    @ns.response(400, 'Erreur de validation des données')
    @ns.response(403, 'Accès interdit')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def post(self):
        """Créer un nouveau livrable"""
        data = api.payload
        return DeliverableService.create_deliverable(
            name=data['name'],
            description=data.get('description'),
            date_closure=data.get('date_closure'),
            id_project=data['id_project']
        ), 201
    
@ns.route('/<int:deliverable_id>')
@ns.param('deliverable_id', 'ID du livrable')
class DeliverableList(Resource):
    @ns.marshal_with(Deliverable.get_model())
    @ns.response(200, 'Livrable trouvé')
    @ns.response(404, 'Livrable non trouvé')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self, deliverable_id):
        """Récupérer un livrable par son ID"""
        deliverable = DeliverableService.get_deliverable_by_id(deliverable_id)
        if not deliverable:
            api.abort(404, "Livrable non trouvé")
        return deliverable, 200
    
    @ns.expect(api.model('DeliverableUpdate', {
        'name': fields.String(description='Nom du livrable'),
        'description': fields.String(description='Description du livrable'),
        'date_closure': fields.DateTime(description='Date de clôture du livrable')
    }))
    @ns.response(200, 'Livrable mis à jour avec succès')
    @ns.response(400, 'Erreur de validation des données')
    @ns.response(404, 'Livrable non trouvé')
    @ns.response(403, 'Accès interdit')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def put(self, deliverable_id):
        """Mettre à jour un livrable existant"""
        data = api.payload
        deliverable = DeliverableService.update_deliverable(
            deliverable_id,
            name=data.get('name'),
            description=data.get('description'),
            date_closure=data.get('date_closure')
        )
        return deliverable, 200
    
    @ns.marshal_with(Deliverable.get_model())
    @ns.response(204, 'Livrable supprimé avec succès')
    @ns.response(404, 'Livrable non trouvé')
    @ns.response(403, 'Accès interdit')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    @roles_required('admin', 'teacher')
    def delete(self, deliverable_id):
        """Supprimer un livrable"""
        return DeliverableService.delete_deliverable(deliverable_id), 204