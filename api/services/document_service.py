from ..models.document import Document, db

class DocumentService:
    @staticmethod
    def get_all_documents(query={}):
        """Récupère tous les documents de la base de données."""
        q = Document.query

        for key, value in query.items():
            q = q.filter(getattr(Document, key).ilike(f"%{value}%"))  

        return q.all()
    
    @staticmethod
    def get_document_by_id(id_document):
        """Récupère un document par son ID."""
        return Document.query.get(id_document)
    
    @staticmethod
    def create_document(name, data, file_type, id_user, id_deliverable):
        """Crée un nouveau document dans la base de données."""
        document = Document(
            name=name,
            data=data,
            file_type=file_type,
            id_user=id_user,
            id_deliverable=id_deliverable
        )
        
        db.session.add(document)
        db.session.commit()
        return document
    
    @staticmethod
    def delete_document(id_document):
        """Supprime un document de la base de données."""
        document = DocumentService.get_document_by_id(id_document)
        db.session.delete(document)
        db.session.commit()
        return document