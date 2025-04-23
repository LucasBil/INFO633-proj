from ..models.work import Work, db
from .user_service import UserService
from .project_service import ProjectService

class WorkService:
    @staticmethod
    def get_all_works(query={}):
        """Récupère tous les liaisons entre les users et les projets de la base de données."""
        q = Work.query

        for key, value in query.items():
            q = q.filter(getattr(Work, key) == value)  

        return q.all()
    
    @staticmethod
    def create_work(user_id, project_id):
        """Crée une nouvelle liaison entre un user et un projet."""
        user = UserService.get_user_by_id(user_id)
        project = ProjectService.get_project_by_id(project_id)
        existing_work = Work.query.filter_by(id_user=user_id, id_project=project_id).first()
        if existing_work:
            raise ValueError("La liaison entre cet utilisateur et ce projet existe déjà.")
        if not user:
            raise ValueError("L'utilisateur avec cet ID n'existe pas.")
        if not project:
            raise ValueError("Le projet avec cet ID n'existe pas.")
        
        work = Work(id_user=user_id, id_project=project_id)
        db.session.add(work)
        db.session.commit()
        
        return work
    
    @staticmethod
    def delete_work(user_id, project_id):
        """Supprime une liaison entre un user et un projet."""
        work = Work.query.filter_by(id_user=user_id, id_project=project_id).first()
        if not work:
            raise ValueError("La liaison entre cet utilisateur et ce projet n'existe pas.")
        
        db.session.delete(work)
        db.session.commit()
        
        return work