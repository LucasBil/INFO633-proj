from api.extensions import db, api
from .project import Project
from flask_restx import fields

class Deliverable(db.Model):
    __tablename__ = 'Deliverable'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=True)
    date_creation = db.Column(db.DateTime, nullable=False, default=db.func.current_timestamp())
    date_closure = db.Column(db.DateTime, nullable=True)

    id_project = db.Column(db.Integer, db.ForeignKey('Project.id'), nullable=False)

    project = db.relationship('Project', back_populates='deliverables')
    documents = db.relationship('Document', back_populates='deliverable')

    @staticmethod
    def get_model():
        return api.model('Deliverable', {
                'id': fields.Integer(description='Identifiant du livrable'),
                'name': fields.String(description='Nom du livrable'),
                'description': fields.String(description='Description du livrable'),
                'date_creation': fields.DateTime(description='Date de création du livrable'),
                'date_closure': fields.DateTime(description='Date de clôture du livrable'),
                'project': fields.Nested(Project.get_model(), description='Projet associé au livrable')
            })