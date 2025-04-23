from enum import Enum
from api.extensions import db, api
from flask_restx import fields

class Role(str, Enum):
    ADMIN = 'admin'
    TECHNICIAN = 'technician'
    TEACHER = 'teacher'
    STUDENT = 'student'

class User(db.Model):
    __tablename__ = 'User'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    email = db.Column(db.String(120), unique=True, nullable=False)
    password = db.Column(db.String(255), nullable=False)
    first_name = db.Column(db.String(50), nullable=False)
    last_name = db.Column(db.String(50), nullable=False)
    roles = db.Column(db.JSON, nullable=False, default=lambda: [Role.STUDENT.value])

    projects_created = db.relationship('Project', back_populates='creator')
    works = db.relationship('Work', back_populates='user')
    documents = db.relationship('Document', back_populates='user')

    @property
    def role_objects(self):
        return [Role(role) for role in self.roles]

    def has_role(self, role: Role) -> bool:
        return role.value in self.roles

    def add_role(self, role: Role):
        if role.value not in self.roles:
            self.roles.append(role.value)

    def remove_role(self, role: Role):
        if role.value in self.roles:
            self.roles.remove(role.value)

    @staticmethod
    def get_model():
        return api.model('User', {
            'id': fields.Integer(description='Identifiant de l\'utilisateur'),
            'email': fields.String(description='Email de l\'utilisateur'),
            'first_name': fields.String(description='Prénom de l\'utilisateur'),
            'last_name': fields.String(description='Nom de famille de l\'utilisateur'),
        })