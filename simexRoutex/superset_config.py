FEATURE_FLAGS = {
    "EMBEDDED_SUPERSET": True,
}

ENABLE_CORS = True

TALISMAN_ENABLED = False

THEME_OVERRIDES = {
    "colors": {
        "primary": "#000000"
    }
}

APP_INITIAL_STATE = {
    "theme": "light"
}

TALISMAN_CONFIG = {
    "content_security_policy": {
        "frame-ancestors": ["http://localhost:8080"]
    }
}

CORS_OPTIONS = {
    "supports_credentials": True,
    "origins": ["http://localhost:8080"]
}

# 🔥 CLAVE
ENABLE_PROXY_FIX = True
PREFERRED_URL_SCHEME = "http"
SESSION_COOKIE_SECURE = False
WTF_CSRF_SSL_STRICT = False

# Seguridad
GUEST_ROLE_NAME = "Public"
PUBLIC_ROLE_LIKE = "Gamma"
GUEST_TOKEN_JWT_SECRET = "simex_secret_token_12345"
GUEST_TOKEN_JWT_ALGO = "HS256"