from flask_app.routes import bp

@bp.route('/ping')
def ping():
    return "pong"
