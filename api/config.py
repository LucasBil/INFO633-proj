from dotenv import load_dotenv
import os

load_dotenv()

class Config:
    # Configuration JWT
    JWT_TOKEN_LOCATION = ['headers']
    JWT_SECRET_KEY = os.getenv('JWT_SECRET_KEY')
    JWT_ACCESS_TOKEN_EXPIRES = 3600  # 1 heure en secondes
    PROPAGATE_EXCEPTIONS = True

    # Configuration de la base de données
    SQLALCHEMY_DATABASE_URI = os.getenv('SQLALCHEMY_DATABASE_URI')
    SQLALCHEMY_TRACK_MODIFICATIONS = False
    SQLALCHEMY_ECHO = False

    # Upload folder
    UPLOAD_FOLDER = os.getenv('UPLOAD_FOLDER', 'api/uploads/')