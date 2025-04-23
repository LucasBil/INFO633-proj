from ..models.project import Project, db
from .user_service import UserService
from ..models.user import Role

class ProjectService:
    @staticmethod
    def get_all_projects(query={}):
        """Récupère tous les projets de la base de données."""
        q = Project.query

        for key, value in query.items():
            q = q.filter(getattr(Project, key).ilike(f"%{value}%"))  

        return q.all()
    
    @staticmethod
    def get_project_by_id(project_id):
        """Récupère un projet par son ID."""
        return Project.query.get(project_id)
    
    @staticmethod
    def create_project(name, description, status, year, duration, id_creator):
        """Crée un nouveau projet dans la base de données."""
        user = UserService.get_user_by_id(id_creator)
        if user is None:
            raise ValueError("L'utilisateur avec cet ID n'existe pas.")
        if not(user.has_role(Role.ADMIN) or user.has_role(Role.TEACHER)):
            raise ValueError("L'utilisateur n'a pas le droit de créer un projet.")
        
        project = Project(
            name=name,
            description=description,
            status=status,
            year=year,
            duration=duration,
            id_creator=id_creator
        )
        
        db.session.add(project)
        db.session.commit()
        return project
    
    @staticmethod
    def update_project(project_id, name=None, description=None, status=None, year=None, duration=None):
        """Met à jour un projet existant."""
        project = ProjectService.get_project_by_id(project_id)
        if project is None:
            raise ValueError("Le projet avec cet ID n'existe pas.")
        
        if name is not None:
            project.name = name
        if description is not None:
            project.description = description
        if status is not None:
            project.status = status
        if year is not None:
            project.year = year
        if duration is not None:
            project.duration = duration
        
        db.session.commit()
        return project
    
    @staticmethod
    def delete_project(project_id):
        """Supprime un projet de la base de données."""
        project = ProjectService.get_project_by_id(project_id)
        if project is None:
            raise ValueError("Le projet avec cet ID n'existe pas.")
        
        db.session.delete(project)
        db.session.commit()
        return project