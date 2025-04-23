from api.extensions import db, api
from .deliverable import Deliverable
from .user import User
from flask_restx import fields

class Document(db.Model):
    __tablename__ = 'Document'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name = db.Column(db.String(100), nullable=False)
    date_deposition = db.Column(db.DateTime, nullable=False, default=db.func.current_timestamp())
    data = db.Column(db.String(150), nullable=False)
    file_type = db.Column(db.String(50), nullable=True)

    id_user = db.Column(db.Integer, db.ForeignKey('User.id'), nullable=False)
    id_deliverable = db.Column(db.Integer, db.ForeignKey('Deliverable.id'), nullable=False)

    user = db.relationship('User', back_populates='documents')
    deliverable = db.relationship('Deliverable', back_populates='documents')

    @staticmethod
    def get_model():
        """Récupère le modèle de la classe Document."""
        return api.model('Document', {
            'id': fields.Integer(readOnly=True, description='Identifiant du document'),
            'name': fields.String(required=True, description='Nom du document'),
            'date_deposition': fields.DateTime(description='Date de déposition du document'),
            'data': fields.String(required=True, description='Données du document'),
            'file_type': fields.String(required=True, description='Type de fichier du document'),
            'user': fields.Nested(User.get_model(), description='Utilisateur associé au document'),
            'deliverable': fields.Nested(Deliverable.get_model(), description='Livrable associé au document')
        })