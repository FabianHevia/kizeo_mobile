from flask import Flask, render_template, request, redirect, url_for, session, flash
import MySQLdb
from werkzeug.security import generate_password_hash, check_password_hash
from datetime import timedelta
import os

app = Flask(__name__)
app.secret_key = os.urandom(24)  # Clave secreta para la sesión
app.permanent_session_lifetime = timedelta(minutes=30)

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
            if user['password']:  # Verificar que la contraseña hasheada exista
                if check_password_hash(user['password'], password):
                    # La contraseña es correcta, se inicia la sesión
                    session.permanent = True  # Hacer la sesión permanente
                    session['username'] = user['username']
                    session['admin'] = user['admin1']

                    flash(f"Inicio de sesión exitoso. Bienvenido, {user['username']}!", "success")
                    return redirect(url_for('datos'))  # Redirigir al menú principal
                else:
                    flash("Contraseña incorrecta.", "error")
            else:
                flash("El nombre de usuario o email no existe.", "error")
        else:
            flash("El nombre de usuario o email no existe.", "error")

        # Si algo falla, devolver el formulario de login
        return render_template('index.html')

    # Para método GET, mostrar el formulario de inicio de sesión
    return render_template('index.html')

@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['username'].strip()
        email = request.form['email'].strip()
        password = request.form['password']
        confirm_password = request.form['confirmPassword']
        
        # Validar si las contraseñas coinciden
        if password != confirm_password:
            flash("Las contraseñas no coinciden.", "error")
            return redirect(url_for('register'))

        # Hashear la contraseña
        hashed_password = generate_password_hash(password)

        # Comprobar si el email ya existe en la base de datos
        cursor = db.cursor(MySQLdb.cursors.DictCursor)
        cursor.execute("SELECT * FROM login_presup WHERE email = %s", (email,))
        account = cursor.fetchone()

        if account:
            flash("El email ya está registrado.", "error")
        else:
            # Insertar nuevo usuario en la base de datos
            cursor.execute("INSERT INTO login_presup (username, email, password) VALUES (%s, %s, %s)",
                           (username, email, hashed_password))
            db.commit()
            flash("Registro exitoso. Ya puedes iniciar sesión.", "success")
            return redirect(url_for('login'))

    return render_template('register.html')

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
    form_id_selected = request.args.get('form_id', type=int)

    # Construir la consulta para obtener los casos
    sql_cases = """
        SELECT ic.id_caso, ic.username, ic.fecha, ic.id_form, ifp.nombre_form 
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

@app.route('/form_presupuestos/<int:id_caso>/<int:id_form>/', methods=['GET', 'POST'])
def editar_formulario(id_caso, id_form):
    # Verificar si la sesión está activa
    if 'username' not in session:
        return "Error: Su sesión ha expirado.", 403

    if request.method == 'POST':
        action = request.form.get('action')

        if action == 'guardar':
            # Validar que los campos requeridos no estén vacíos
            if all(request.form.get(field) for field in ['nombre_recinto', 'ancho_recinto', 'largo_recinto', 'alto_recinto']):
                # Recoger datos del formulario
                id_form = request.form.get('id_form')
                nombre_recinto = request.form.get('nombre_recinto')
                ancho_recinto = request.form.get('ancho_recinto')
                largo_recinto = request.form.get('largo_recinto')
                alto_recinto = request.form.get('alto_recinto')

                # Recoger datos de checkboxes
                reparacion_cielo = 1 if request.form.get('reparacion_cielo') else 0
                reparacion_piso = 1 if request.form.get('reparacion_piso') else 0
                reparacion_muro = 1 if request.form.get('reparacion_muro') else 0
                reparacion_cornisa = 1 if request.form.get('reparacion_cornisa') else 0
                retiro_mobiliario = 1 if request.form.get('retiro_mobiliario') else 0

                # Recoger observaciones
                observaciones = request.form.get('observaciones', '')

                # Manejo de las fotos
                foto1 = request.files.get('foto1')
                foto2 = request.files.get('foto2')

                # Guardar fotos en una carpeta
                target_dir = f"img_interiores/{id_caso}/"
                os.makedirs(target_dir, exist_ok=True)  # Crear la carpeta si no existe

                # Guardar fotos
                imagen_recinto1 = None
                imagen_recinto2 = None
                
                if foto1:
                    foto1.save(os.path.join(target_dir, foto1.filename))
                    imagen_recinto1 = foto1.filename

                if foto2:
                    foto2.save(os.path.join(target_dir, foto2.filename))
                    imagen_recinto2 = foto2.filename

                # Guardar en la base de datos
                cursor = db.cursor(MySQLdb.cursors.DictCursor)
                cursor.execute("""
                    INSERT INTO presp_interiores (id_caso, id_form, nombre_recinto, ancho_recinto, largo_recinto, alto_recinto, 
                    reparacion_cielo, reparacion_piso, reparacion_muro, reparacion_cornisa, 
                    retiro_instalacion_mobiliario, imagen_recinto1, imagen_recinto2, observaciones)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """, (id_caso, id_form, nombre_recinto, ancho_recinto, largo_recinto, alto_recinto,
                      reparacion_cielo, reparacion_piso, reparacion_muro, reparacion_cornisa,
                      retiro_mobiliario, imagen_recinto1, imagen_recinto2, observaciones))

                db.commit()  # Asegúrate de guardar los cambios
                cursor.close()

                flash("Datos guardados correctamente.")
                return redirect(url_for('editar_formulario', id_caso=id_caso, id_form=id_form))
            else:
                flash("Por favor, completa todos los campos requeridos correctamente.")
                return redirect(url_for('editar_formulario', id_caso=id_caso, id_form=id_form))

        elif action == 'enviar':
            # Recoger datos del formulario para la acción "enviar"
            fecha = request.form.get('fecha')
            caso = request.form.get('caso')
            siniestro = request.form.get('siniestro')
            folio = request.form.get('folio')
            metros = request.form.get('metros')
            antiguedad = request.form.get('antiguedad')
            liquidadora = request.form.get('liquidadora')
            inspector = request.form.get('inspector')
            evaluador = request.form.get('evaluador')
            email_evaluador = request.form.get('email_evaluador')
            nombre = request.form.get('nombre')
            rut = request.form.get('rut')
            direccion = request.form.get('direccion')
            comuna = request.form.get('comuna')
            ciudad = request.form.get('ciudad')

            # Checkboxes (convertir a 1 o 0)
            fachada_frontal = 1 if request.form.get('fachadaFrontal') else 0
            fachada_trasera = 1 if request.form.get('fachadaTrasera') else 0
            fachada_derecha = 1 if request.form.get('fachadaDerecha') else 0
            fachada_izquierda = 1 if request.form.get('fachadaIzquierda') else 0
            reparacion_techo = 1 if request.form.get('reparacionTecho') else 0
            reacomodo_tejas = 1 if request.form.get('reacomodoTejas') else 0
            reparacion_cierre_perimetral = 1 if request.form.get('reparacionCierrePerimetral') else 0
            reparacion_marcos_puertas = 1 if request.form.get('reparacionMarcosPuertas') else 0
            reparacion_radier = 1 if request.form.get('reparacionRadier') else 0

            # Actualizar en la base de datos
            cursor = db.cursor(MySQLdb.cursors.DictCursor)
            sql = """
                UPDATE presp_formularios 
                SET fecha = ?, 
                    n_caso = ?, 
                    n_folio = ?, 
                    metros_cuadrados = ?, 
                    antiguedad_domicilio = ?, 
                    liquidadora = ?, 
                    inspector = ?, 
                    evaluador = ?, 
                    email_evaluador = ?, 
                    cliente_nombre = ?, 
                    cliente_rut = ?, 
                    cliente_direccion = ?, 
                    cliente_comuna = ?, 
                    cliente_ciudad = ?, 
                    fachada_frontal = ?, 
                    fachada_trasera = ?, 
                    fachada_derecha = ?, 
                    fachada_izquierda = ?, 
                    reparacion_techo = ?, 
                    reacomodo_tejas = ?, 
                    cierre_perimetral = ?, 
                    marcos_puertas = ?, 
                    radier = ?
                WHERE id_caso = ?
            """

            cursor.execute(sql, (
                fecha, caso, folio, metros, antiguedad, liquidadora, inspector,
                evaluador, email_evaluador, nombre, rut, direccion, comuna,
                ciudad, fachada_frontal, fachada_trasera, fachada_derecha,
                fachada_izquierda, reparacion_techo, reacomodo_tejas,
                reparacion_cierre_perimetral, reparacion_marcos_puertas,
                reparacion_radier, id_caso
            ))

            db.commit()  # Asegúrate de guardar los cambios
            cursor.close()

            flash("Registro actualizado correctamente.")
            return redirect(url_for('editar_formulario', id_caso=id_caso, id_form=id_form))

    else:
        cursor = db.cursor(MySQLdb.cursors.DictCursor)
        # Consultar los valores actuales del formulario desde la base de datos
        sql_formulario = "SELECT * FROM presp_formularios WHERE id_caso = %s AND id_form = %s"
        cursor.execute(sql_formulario, (id_caso, id_form))
        formulario = cursor.fetchone()

        # Si existe el formulario, proceder con la segunda consulta
        if formulario:
            # Consulta para obtener los datos de interiores
            sql_interiores = """
                SELECT nombre_recinto, ancho_recinto, largo_recinto, alto_recinto, 
                    reparacion_cielo, reparacion_piso, reparacion_muro, reparacion_cornisa, 
                    retiro_instalacion_mobiliario, imagen_recinto1, imagen_recinto2, observaciones
                FROM presp_interiores
                WHERE id_caso = %s AND id_form = %s
            """
            cursor.execute(sql_interiores, (id_caso, id_form))
            interiores = cursor.fetchall()  # Se obtienen todos los registros

            # Renderizar la plantilla HTML con los datos obtenidos
            return render_template('form_presupuestos.html', formulario=formulario, interiores=interiores)
    # Si es una solicitud GET, renderiza el formulario
    return render_template('form_presupuestos.html', id_caso=id_caso, id_form=id_form)  # Asegúrate de crear este template

@app.route('/ver_detalles/<int:id_caso>/<int:id_form>')
def ver_detalles(id_caso, id_form):
    # Lógica para ver los detalles del caso
    pass

@app.route('/descargar_presupuesto/<int:id_caso>/<int:id_form>')
def descargar_presupuesto(id_caso, id_form):
    # Lógica para descargar el presupuesto
    pass

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)