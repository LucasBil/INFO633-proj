from functools import wraps
from flask_jwt_extended import verify_jwt_in_request, get_jwt
from ..extensions import api

def roles_required(*required_roles):
    def wrapper(fn):
        @wraps(fn)
        def decorator(*args, **kwargs):
            verify_jwt_in_request()
            roles = get_jwt().get('roles', [])

            if not any(role in roles for role in required_roles):
                return api.abort(403, f"Access forbidden: requires one of the following roles: {', '.join(required_roles)}")
            
            return fn(*args, **kwargs)
        return decorator
    return wrapper