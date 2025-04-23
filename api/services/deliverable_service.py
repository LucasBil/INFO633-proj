from ..models.deliverable import Deliverable, db

class DeliverableService:
    @staticmethod
    def get_all_deliverables(query={}):
        """Récupère tous les livrables de la base de données."""
        q = Deliverable.query

        for key, value in query.items():
            q = q.filter(getattr(Deliverable, key).ilike(f"%{value}%"))  

        return q.all()
    
    @staticmethod
    def get_deliverable_by_id(deliverable_id):
        """Récupère un livrable par son ID."""
        return Deliverable.query.get(deliverable_id)
    
    @staticmethod
    def create_deliverable(name, description, date_closure, id_project):
        """Crée un nouveau livrable dans la base de données."""
        deliverable = Deliverable(
            name=name,
            description=description,
            date_closure=date_closure,
            id_project=id_project
        )
        
        db.session.add(deliverable)
        db.session.commit()
        return deliverable
    
    @staticmethod
    def update_deliverable(deliverable_id, name=None, description=None, date_closure=None):
        """Met à jour un livrable existant."""
        deliverable = DeliverableService.get_deliverable_by_id(deliverable_id)
        if deliverable is None:
            raise ValueError("Le livrable avec cet ID n'existe pas.")
        
        if name is not None:
            deliverable.name = name
        if description is not None:
            deliverable.description = description
        if date_closure is not None:
            deliverable.date_closure = date_closure
        
        db.session.commit()
        return deliverable
    
    @staticmethod
    def delete_deliverable(deliverable_id):
        """Supprime un livrable de la base de données."""
        deliverable = DeliverableService.get_deliverable_by_id(deliverable_id)
        if deliverable is None:
            raise ValueError("Le livrable avec cet ID n'existe pas.")
        
        db.session.delete(deliverable)
        db.session.commit()
        return deliverable