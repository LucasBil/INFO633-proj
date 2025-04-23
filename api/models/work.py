from api.extensions import db, api
from flask_restx import fields
from .user import User
from .project import Project

class Work(db.Model):
    __tablename__ = 'Work'

    id_user = db.Column(db.Integer, db.ForeignKey('User.id'), primary_key=True, nullable=False)
    id_project = db.Column(db.Integer, db.ForeignKey('Project.id'), primary_key=True, nullable=False)

    user = db.relationship('User', back_populates='works')
    project = db.relationship('Project', back_populates='works')

    @staticmethod
    def get_model():
        return api.model('Work', {
            'user': fields.Nested(User.get_model()),
            'project': fields.Nested(Project.get_model()),
        })