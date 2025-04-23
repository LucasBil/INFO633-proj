from flask import request, send_file
from flask_restx import Resource, Namespace, fields
from flask_jwt_extended import jwt_required, get_jwt
from werkzeug.utils import secure_filename
import os

from ..services.document_service import DocumentService
from ..services.deliverable_service import DeliverableService
from ..models.document import Document
from ..extensions import api
from ..config import Config
import zipfile
from io import BytesIO

ns = Namespace('document', description='Opérations sur les documents')
# TODO : secure route for other students

@ns.route('/')
class DocumentList(Resource):
    @ns.marshal_list_with(Document.get_model())
    @ns.param('name', 'Filtrer par nom')
    @ns.param('date_deposition', 'Filtrer par date de déposition')
    @ns.param('file_type', 'Filtrer par type de fichier')
    @ns.param('id_user', 'Filtrer par ID d\'utilisateur')
    @ns.param('id_deliverable', 'Filtrer par ID de livrable')
    @ns.response(200, 'Liste des documents')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self):
        """Récupérer la liste des documents avec/sans des filtres"""
        search_query = {
            'name': request.args.get('name'),
            'date_deposition': request.args.get('date_deposition'),
            'file_type': request.args.get('file_type'),
            'id_user': request.args.get('id_user'),
            'id_deliverable': request.args.get('id_deliverable')
        }
        query = {k: v for k, v in search_query.items() if v}
        return DocumentService.get_all_documents(query), 200
    
@ns.route('/<int:id_document>')
@ns.param('id_document', 'ID du document')
class DocumentResource(Resource):
    @ns.marshal_with(Document.get_model())
    @ns.response(200, 'Document récupéré')
    @ns.response(404, 'Document non trouvé')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self, id_document):
        """Récupérer un document par son ID"""
        document = DocumentService.get_document_by_id(id_document)
        if not document:
            return api.abort(404, 'Document non trouvé')
        return document, 200
    
    @ns.marshal_with(Document.get_model())
    @ns.response(204, 'Document supprimé')
    @ns.response(404, 'Document non trouvé')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def delete(self, id_document):
        """Supprimer un document par son ID"""
        document = DocumentService.get_document_by_id(id_document)
        if document.file_type != None:
            folder = os.path.dirname(document.data)
            filename = os.path.basename(document.name)
            file_path = os.path.join(folder, filename)
            if os.path.exists(file_path):
                os.remove(file_path)

        document = DocumentService.delete_document(id_document)
        return document, 204

@ns.route('/upload/file/<int:id_deliverable>')
@ns.param('id_deliverable', 'ID de livrable')
class DocumentUploadFile(Resource):
    @ns.marshal_with(Document.get_model(), code=201)
    @ns.response(201, 'Document uploadé')
    @ns.response(400, 'Erreur de validation')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def post(self, id_deliverable):
        """Uploader un document"""
        file = request.files.get('file')
        filename = secure_filename(file.filename)
        file_type = filename.split('.')[-1]
        deliverable = DeliverableService.get_deliverable_by_id(id_deliverable)
        folder = os.path.join(Config.UPLOAD_FOLDER, f"{deliverable.project.id}/{deliverable.id}")
        os.makedirs(folder, exist_ok=True)
        file.save(os.path.join(folder, filename))
        document = DocumentService.create_document(
            name=filename,
            data=f"{folder}/{filename}",
            file_type=file_type,
            id_user=get_jwt()["id"],
            id_deliverable=id_deliverable
        )
        return document, 201
    
@ns.route('/download/files/<int:id_deliverable>')
@ns.param('id_deliverable', 'ID de livrable')
class DocumentDownloadFiles(Resource):
    @ns.response(200, 'Document téléchargé')
    @ns.response(400, 'Erreur de validation')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self, id_deliverable):
        """Télécharger les documents d\'un livrable sous forme de zip"""
        deliverable = DeliverableService.get_deliverable_by_id(id_deliverable)
        if not deliverable:
            return api.abort(404, 'Livrable non trouvé')
        documents = DocumentService.get_all_documents({'id_deliverable': id_deliverable})
        if not documents:
            return api.abort(404, 'Aucun document trouvé pour ce livrable')

        memory_file = BytesIO()
        with zipfile.ZipFile(memory_file, 'w', zipfile.ZIP_DEFLATED) as zf:
            for document in documents:
                if document.file_type is None:
                    continue
                folder = os.path.dirname(document.data)
                filename = os.path.basename(document.name)
                file_path = os.path.join(folder, filename)
                if not os.path.exists(file_path):
                    return api.abort(404, f"Le fichier {filename} n'existe pas")
                zf.write(file_path, arcname=filename)
        memory_file.seek(0)

        worker_names = list(map(lambda user: user.last_name.upper(), deliverable.project.getworkers()))
        return send_file(
            memory_file,
            as_attachment=True,
            download_name=f"{deliverable.project.name}_{id_deliverable}_{'_'.join(worker_names)}.zip"
        )
    
@ns.route('/download/file/<int:id_document>')
@ns.param('id_document', 'ID du document')
class DocumentDownloadFiles(Resource):
    @ns.response(200, 'Document téléchargé')
    @ns.response(400, 'Erreur de validation')
    @ns.response(401, 'Non autorisé')
    @jwt_required()
    def get(self, id_document):
        """Télécharger un document"""
        document = DocumentService.get_document_by_id(id_document)
        if not document:
            return api.abort(404, 'Document non trouvé')
        if document.file_type is None:
            return api.abort(404, 'Type de fichier non trouvé')
        if not os.path.exists(document.data):
            return api.abort(404, 'Le fichier n\'existe pas')
        return send_file(
            document.data.replace("api/", ""),
            as_attachment=True,
            download_name=document.name
        )