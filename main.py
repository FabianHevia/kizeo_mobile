from flask import Flask, render_template, request, redirect, url_for, session, flash
import MySQLdb
from werkzeug.security import check_password_hash
import os

app = Flask(__name__)
app.secret_key = os.urandom(24)  # Clave secreta para la sesión

# Conexión a la base de datos
db = MySQLdb.connect(
    host="162.214.199.14",
    user="crmcubic_presupas",
    passwd="123q1q2q3Q",
    db="crmcubic_presup"
)

@app.route('/', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        # Recoger los datos del formulario
        userEmail = request.form['userEmail'].strip()
        password = request.form['loginPassword']

        # Preparar y ejecutar la consulta para encontrar el usuario
        cursor = db.cursor(MySQLdb.cursors.DictCursor)
        sql = "SELECT * FROM login_presup WHERE username = %s OR email = %s"
        cursor.execute(sql, (userEmail, userEmail))
        user = cursor.fetchone()

        if user:
            if user and user['password']: # Verificar la contraseña hasheada
                if check_password_hash(user['password'], password):
                    # La contraseña es correcta, se inicia la sesión
                    session['user_id'] = user['user_id']
                    session['username'] = user['username']
                    session['admin'] = user['admin']

                    flash(f"Inicio de sesión exitoso. Bienvenido, {user['username']}!", "success")
                    return redirect(url_for('datos'))  # Redirigir al menú principal
                else:
                    flash("Contraseña incorrecta.", "error")
                    
            else:
                flash("El nombre de usuario o email no existe.", "error")

        return render_template('index.html')

# Ruta para la página de datos
@app.route('/datos', methods=['GET'])
def datos():
    # Verificar si el usuario ha iniciado sesión
    if 'username' not in session:
        # Si no hay sesión, redirigir al login
        return redirect(url_for('login'))

    username = session['username']

    # Conectar a la base de datos
    cursor = db.cursor(MySQLdb.cursors.DictCursor)

    # Consultar si el usuario es administrador
    sql_admin = "SELECT admin1 FROM login_presup WHERE username = %s"
    cursor.execute(sql_admin, [username])
    user_info = cursor.fetchone()

    if not user_info:
        flash("Error obteniendo la información del usuario.", "error")
        return redirect(url_for('login'))

    is_admin = user_info['admin1']

    # Obtener lista de formularios
    sql_forms = "SELECT id_form, nombre_form FROM info_forms_presup"
    cursor.execute(sql_forms)
    forms = cursor.fetchall()

    # Obtener el formulario seleccionado (si existe)
    form_id_selected = request.args.get('form_id')

    # Construir la consulta para obtener los casos
    sql_cases = """
        SELECT ic.id_caso, ic.username, ic.fecha, ifp.nombre_form 
        FROM info_casos_presup AS ic
        JOIN info_forms_presup AS ifp ON ic.id_form = ifp.id_form
    """

    params = []
    if form_id_selected:
        sql_cases += " WHERE ic.id_form = %s"
        params.append(form_id_selected)

    # Ejecutar la consulta de casos
    cursor.execute(sql_cases, params)
    cases = cursor.fetchall()

    cursor.close()

    return render_template('datos.html', forms=forms, cases=cases, is_admin=is_admin, form_id_selected=form_id_selected)

@app.route('/menu')
def menu():
    # Lógica para la página de ingresar casos
    return render_template('menu.html')

@app.route('/permisos')
def permisos():
    # Lógica para la página de gestión de permisos (solo accesible para admin)
    return render_template('permisos.html')

@app.route('/editar_formulario/<int:id_caso>/<int:id_form>')
def editar_formulario(id_caso, id_form):
    # Lógica para editar el formulario
    pass

@app.route('/ver_detalles/<int:id_caso>/<int:id_form>')
def ver_detalles(id_caso, id_form):
    # Lógica para ver los detalles del caso
    pass

@app.route('/descargar_presupuesto/<int:id_caso>/<int:id_form>')
def descargar_presupuesto(id_caso, id_form):
    # Lógica para descargar el presupuesto
    pass

if __name__ == '__main__':
    app.run(debug=True)