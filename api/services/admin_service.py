from werkzeug.security import generate_password_hash
from ..models.user import User, db

class AdminService:
    @staticmethod
    def update_user(user_id, email=None, password=None, first_name=None, last_name=None, roles=None):
        """Met à jour les informations d'un utilisateur."""
        user = User.query.get(user_id)
        if not user:
            return None
        
        if email:
            user.email = email
        if password:
            user.password = generate_password_hash(password)
        if first_name:
            user.first_name = first_name
        if last_name:
            user.last_name = last_name
        if roles is not None:
            user.roles = roles
        
        db.session.commit()
        return user
    
    @staticmethod
    def delete_user(user_id):
        """Supprime un utilisateur de la base de données."""
        user = User.query.get(user_id)
        if not user:
            return None
        
        db.session.delete(user)
        db.session.commit()
        return user