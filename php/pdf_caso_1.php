<?php
// Iniciar la sesión
session_start();

// Conexión a la base de datos
$servername = "162.214.199.14";
$username_db = "crmcubic_presupas";
$password_db = "123q1q2q3Q";
$dbname = "crmcubic_presup";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se pasó un id_caso por la URL
if (isset($_GET['id_caso'])) {
    $id_caso = $_GET['id_caso'];  // Capturar id_caso desde el parámetro de la URL
} else {
    die("No se ha proporcionado un ID de caso.");
}

// Suponemos que ya tienes el nombre de usuario en la sesión
$username = $_SESSION['username'];  // Nombre de usuario obtenido de la sesión

// Consultar si el usuario es administrador
$sql = "SELECT admin1 FROM login_presup WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);  // "s" porque es un string
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

// Consultar los datos del caso específico
$sql = "SELECT fecha, n_caso, n_folio, metros_cuadrados, antiguedad_domicilio, liquidadora, inspector, evaluador, email_evaluador, cliente_nombre, cliente_rut, cliente_direccion, cliente_comuna, cliente_ciudad, fachada_frontal, fachada_trasera, fachada_derecha, fachada_izquierda, reparacion_techo, reacomodo_tejas, cierre_perimetral, marcos_puertas, radier 
        FROM presp_formularios 
        WHERE id_caso = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_caso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los datos de la fila
    $row = $result->fetch_assoc();

    // Asignar valores a variables
    $fecha = $row['fecha'];
    $caso = $row['n_caso'];
    $folio = $row['n_folio'];
    $metros = $row['metros_cuadrados'];
    $antiguedad = $row['antiguedad_domicilio'];
    $liquidadora = $row['liquidadora'];
    $inspector = $row['inspector'];
    $evaluador = $row['evaluador'];
    $email_evaluador = $row['email_evaluador'];
    $nombre = $row['cliente_nombre'];
    $rut = $row['cliente_rut'];
    $direccion = $row['cliente_direccion'];
    $comuna = $row['cliente_comuna'];
    $ciudad = $row['cliente_ciudad'];
    $fachadaFrontal = $row['fachada_frontal'];
    $fachadaTrasera = $row['fachada_trasera'];
    $fachadaDerecha = $row['fachada_derecha'];
    $fachadaIzquierda = $row['fachada_izquierda'];
    $reparacionTecho = $row['reparacion_techo'];
    $reacomodoTejas = $row['reacomodo_tejas'];
    $reparacionCierrePerimetral = $row['cierre_perimetral'];
    $reparacionMarcosPuertas = $row['marcos_puertas'];
    $reparacionRadier = $row['radier'];
} else {
    die("No se encontraron datos para el ID de caso proporcionado.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF caso <?= htmlspecialchars($caso) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .wrapper {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        .content {
            margin-left: 250px; /* mismo ancho que el sidebar */
            padding: 20px;
            width: calc(100% - 250px);
        }
        @media (width < 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .content {
                margin-left: 0;
                width: 100%;
            }
            .sidebar-collapse {
                display: grid;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="mt-5">Resumen de Casos</h2>
    
        <!-- Información del Usuario y Fecha -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nombre del Usuario</th>
                    <th>RUT</th>
                    <th>Fecha de Creación</th>
                    <th>Tipo de Formulario</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($nombre) ?></td>
                    <td><?= htmlspecialchars($rut) ?></td>
                    <td><?= htmlspecialchars($fecha) ?></td>
                    <td><?= htmlspecialchars($folio) ?></td>
                </tr>
            </tbody>
        </table>
    
        <!-- Tabla de Fachadas -->
        <h4 class="mt-5">Fachadas</h4>
        <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fachada</th>
                        <th>Reparar?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Frontal</td>
                        <td><?= $fachadaFrontal ? 'Sí' : 'No' ?></td>
                    </tr>
                    <tr>
                        <td>Trasera</td>
                        <td><?= $fachadaTrasera ? 'Sí' : 'No' ?></td>
                    </tr>
                    <tr>
                        <td>Lateral Izquierda</td>
                        <td><?= $fachadaIzquierda ? 'Sí' : 'No' ?></td>
                    </tr>
                    <tr>
                        <td>Lateral Derecha</td>
                        <td><?= $fachadaDerecha ? 'Sí' : 'No' ?></td>
                    </tr>
                </tbody>
            </table>
    
       <!-- Tabla de Techumbre -->
       <h4 class="mt-5">Techumbre</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Reacomodo Tejas</th>
                        <th>Reparación de Techo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Reparar?</th>
                        <td><?= $reparacionTecho ? 'Sí' : 'No' ?></td>
                        <td><?= $reacomodoTejas ? 'Sí' : 'No' ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Tabla de Cierre Perimetral -->
            <h4 class="mt-5">Cierre Perimetral</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Reparación Cierre Perimetral</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Reparar?</th>
                        <td><?= $reparacionCierrePerimetral ? 'Sí' : 'No' ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Tabla de Marcos de Puertas -->
            <h4 class="mt-5">Marcos de Puertas</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Reparación Marcos de Puertas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Reparar?</th>
                        <td><?= $reparacionMarcosPuertas ? 'Sí' : 'No' ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Tabla de Radier -->
            <h4 class="mt-5">Radier</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Reparación de Radier</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Reparar?</th>
                        <td><?= $reparacionRadier ? 'Sí' : 'No' ?></td>
                    </tr>
                </tbody>
            </table>
    </div>
    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>