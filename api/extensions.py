from flask_sqlalchemy import SQLAlchemy
from flask_restx import Api
from flask_jwt_extended import JWTManager

authorizations = {
    'Bearer Auth': {
        'type': 'apiKey',
        'in': 'header',
        'name': 'Authorization',
        'description': "Type in the 'Bearer ' prefix followed by your token"
    }
}

# Créez les instances sans les lier à une app
db = SQLAlchemy()
api = Api(
    title='INFO633 API',
    version='0.0.1',
    description='API for the INFO633 project',
    security=['Bearer Auth'],
    authorizations=authorizations,
    doc='/docs'
)
jwt = JWTManager()