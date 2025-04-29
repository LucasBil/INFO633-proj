from flask import Flask
from flask_migrate import Migrate
from flask_cors import CORS

from api.config import Config
from api.extensions import db, api, jwt
from api.resources import __all__

def create_app(config_class=Config):
    app = Flask(__name__)
    app.config.from_object(config_class)

    CORS(app)  # Enable CORS for all domains
    # Or for specific origin
    #CORS(app, resources={r"/auth/*": {"origins": "*"}})
    
    # Initialiser les extensions
    db.init_app(app)
    api.init_app(app)
    jwt.init_app(app)
    
    Migrate(app, db)  # Initialiser Flask-Migrate
    
    # Ajouter les namespaces
    for ns in __all__:
        api.add_namespace(ns)
    
    return app

if __name__ == '__main__':
    app = create_app()
    app.run(debug=True)