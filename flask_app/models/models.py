from flask_app.app import db, login
from flask_login import UserMixin
from werkzeug.security import generate_password_hash, check_password_hash

class User(UserMixin, db.Model):
    __tablename__ = 'users'
    user_id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(64), index=True, unique=True, nullable=False)
    email = db.Column(db.String(120), index=True, unique=True)
    password_hash = db.Column(db.String(256))

    def set_password(self, password):
        self.password_hash = generate_password_hash(password)

    def check_password(self, password):
        return check_password_hash(self.password_hash, password)

    def get_id(self):
        return str(self.user_id)

    def to_dict(self):
        return {
            'user_id': self.user_id,
            'username': self.username,
            'email': self.email
        }

@login.user_loader
def load_user(id):
    return User.query.get(int(id))

class Brand(db.Model):
    __tablename__ = 'brands'
    brand_id = db.Column(db.Integer, primary_key=True)
    brand_name = db.Column(db.String(100), nullable=False)
    brand_active = db.Column(db.Integer, default=1)
    brand_status = db.Column(db.Integer, default=1) # 1: Active, 2: Deleted

    articulos = db.relationship('Articulo', backref='brand', lazy='dynamic')

    def to_dict(self):
        return {
            'brand_id': self.brand_id,
            'brand_name': self.brand_name,
            'brand_active': self.brand_active,
            'brand_status': self.brand_status
        }

class Category(db.Model):
    __tablename__ = 'categories'
    categories_id = db.Column(db.Integer, primary_key=True)
    categories_name = db.Column(db.String(100), nullable=False)
    categories_active = db.Column(db.Integer, default=1)
    categories_status = db.Column(db.Integer, default=1)

    articulos = db.relationship('Articulo', backref='category', lazy='dynamic')

    def to_dict(self):
        return {
            'categories_id': self.categories_id,
            'categories_name': self.categories_name,
            'categories_active': self.categories_active,
            'categories_status': self.categories_status
        }

class Color(db.Model):
    __tablename__ = 'colores'
    id_color = db.Column(db.Integer, primary_key=True)
    nombre_color = db.Column(db.String(100), nullable=False)

    articulos = db.relationship('Articulo', backref='color', lazy='dynamic')

    def to_dict(self):
        return {
            'id_color': self.id_color,
            'nombre_color': self.nombre_color
        }

class Ubicacion(db.Model):
    __tablename__ = 'ubicaciones'
    id_ubicacion = db.Column(db.Integer, primary_key=True)
    nombre_ubicacion = db.Column(db.String(100), nullable=False)

    inventarios = db.relationship('Inventario', backref='ubicacion', lazy='dynamic')

    def to_dict(self):
        return {
            'id_ubicacion': self.id_ubicacion,
            'nombre_ubicacion': self.nombre_ubicacion
        }

class Estado(db.Model):
    __tablename__ = 'estados'
    id_estado = db.Column(db.Integer, primary_key=True)
    nombre_estado = db.Column(db.String(100), nullable=False)

    inventarios = db.relationship('Inventario', backref='estado', lazy='dynamic')

    def to_dict(self):
        return {
            'id_estado': self.id_estado,
            'nombre_estado': self.nombre_estado
        }

class Articulo(db.Model):
    __tablename__ = 'articulos'
    id_articulo = db.Column(db.Integer, primary_key=True)
    nombre_articulo = db.Column(db.String(255), nullable=False)
    codigo_interno = db.Column(db.String(100), unique=True, nullable=False)
    id_marca = db.Column(db.Integer, db.ForeignKey('brands.brand_id'))
    id_categoria = db.Column(db.Integer, db.ForeignKey('categories.categories_id'))
    id_color = db.Column(db.Integer, db.ForeignKey('colores.id_color'))
    product_image = db.Column(db.Text)

    inventarios = db.relationship('Inventario', backref='articulo', lazy='dynamic')

    def to_dict(self):
        return {
            'id_articulo': self.id_articulo,
            'nombre_articulo': self.nombre_articulo,
            'codigo_interno': self.codigo_interno,
            'id_marca': self.id_marca,
            'id_categoria': self.id_categoria,
            'id_color': self.id_color,
            'product_image': self.product_image,
            'brand': self.brand.brand_name if self.brand else None,
            'category': self.category.categories_name if self.category else None,
            'color': self.color.nombre_color if self.color else None
        }

class Inventario(db.Model):
    __tablename__ = 'inventario'
    id_inventario = db.Column(db.Integer, primary_key=True)
    id_articulo = db.Column(db.Integer, db.ForeignKey('articulos.id_articulo'), nullable=False)
    id_ubicacion = db.Column(db.Integer, db.ForeignKey('ubicaciones.id_ubicacion'), nullable=False)
    id_estado = db.Column(db.Integer, db.ForeignKey('estados.id_estado'), nullable=False)
    cantidad = db.Column(db.Integer, default=0)
    rate = db.Column(db.String(100))
    activo = db.Column(db.Integer, default=1)
    status = db.Column(db.Integer, default=1) # 2 for logical delete

    def to_dict(self):
        return {
            'id_inventario': self.id_inventario,
            'id_articulo': self.id_articulo,
            'articulo': self.articulo.nombre_articulo if self.articulo else None,
            'codigo_interno': self.articulo.codigo_interno if self.articulo else None,
            'id_ubicacion': self.id_ubicacion,
            'ubicacion': self.ubicacion.nombre_ubicacion if self.ubicacion else None,
            'id_estado': self.id_estado,
            'estado': self.estado.nombre_estado if self.estado else None,
            'cantidad': self.cantidad,
            'rate': self.rate,
            'activo': self.activo,
            'status': self.status
        }
