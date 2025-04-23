# Imports nspace
from .admin import ns as admin_ns
from .auth import ns as auth_ns
from .deliverable import ns as deliverable_ns
from .document import ns as document_ns
from .project import ns as project_ns
from .user import ns as user_ns
from .work import ns as work_ns

__all__ = [
    admin_ns,
    auth_ns,
    deliverable_ns,
    document_ns,
    project_ns,
    user_ns,
    work_ns,
]