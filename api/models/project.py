from enum import Enum
from api.extensions import db, api
from flask_restx import fields
from .user import User

class ProjectStatus(str, Enum):
    NOT_STARTED = 'not_started'
    IN_PROGRESS = 'in_progress'
    COMPLETED = 'completed'
    DISMANTLED = 'dismantled'

class Project(db.Model):
    __tablename__ = 'Project'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=True)
    status = db.Column(db.Enum(ProjectStatus), nullable=False, default=ProjectStatus.NOT_STARTED)
    year = db.Column(db.Integer, nullable=False)
    duration = db.Column(db.Time, nullable=False)

    id_creator = db.Column(db.Integer, db.ForeignKey('User.id'), nullable=False)

    creator = db.relationship('User', back_populates='projects_created')
    works = db.relationship('Work', back_populates='project')
    deliverables = db.relationship('Deliverable', back_populates='project')

    def getworkers(self):
        """Récupère les utilisateurs associés au projet."""
        return [work.user for work in self.works]

    @staticmethod
    def get_model():
        return api.model('Project', {
            'id': fields.Integer(description='Identifiant du projet'),
            'name': fields.String(description='Nom du projet'),
            'description': fields.String(description='Description du projet'),
            'status': fields.String(description='Statut du projet', enum=['not_started', 'in_progress', 'completed', 'dismantled']),
            'year': fields.Integer(description='Année de début du projet'),
            'duration': fields.String(description='Durée du projet'),
            'creator' : fields.Nested(User.get_model()),
            'workers' : fields.List(fields.Nested(api.model('UserWorker', {
                'id': fields.Integer(description='Identifiant de l\'utilisateur', attribute='user.id'),
                'email': fields.String(description='Email de l\'utilisateur', attribute='user.email'),
                'first_name': fields.String(description='Prénom de l\'utilisateur', attribute='user.first_name'),
                'last_name': fields.String(description='Nom de famille de l\'utilisateur', attribute='user.last_name'),
            })), attribute='works')
        })