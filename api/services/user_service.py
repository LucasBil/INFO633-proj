from werkzeug.security import generate_password_hash
from ..models.user import User, db

class UserService:
    @staticmethod
    def create_user(email, password, first_name=None, last_name=None):
        """Crée un nouvel utilisateur dans la base de données."""
        hashed_password = generate_password_hash(password)
        user = User(
            email=email,
            password=hashed_password,
            first_name=first_name,
            last_name=last_name
        )
        
        db.session.add(user)
        db.session.commit()
        return user
    
    @staticmethod
    def update_user(user_id, email=None, first_name=None, last_name=None):
        """Met à jour les informations d'un utilisateur."""
        user = User.query.get(user_id)
        if not user:
            raise ValueError("L'utilisateur avec cet ID n'existe pas.")
        
        if email:
            user.email = email
        if first_name:
            user.first_name = first_name
        if last_name:
            user.last_name = last_name
        
        db.session.commit()
        return user
    
    @staticmethod
    def delete_user(user_id):
        """Supprime un utilisateur de la base de données."""
        user = User.query.get(user_id)
        if not user:
            raise ValueError("L'utilisateur avec cet ID n'existe pas.")
        
        db.session.delete(user)
        db.session.commit()
        return user
    
    @staticmethod
    def get_user_by_id(user_id):
        """Récupère un utilisateur par son ID."""
        return User.query.get(user_id)
    
    @staticmethod
    def get_all_users(query={}):
        """Récupère tous les utilisateurs avec filtres souples."""
        q = User.query

        for key, value in query.items():
            q = q.filter(getattr(User, key).ilike(f"%{value}%"))  

        return q.all()