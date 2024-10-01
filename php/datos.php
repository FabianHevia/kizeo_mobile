<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Si no hay sesión, redirigir al login
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "162.214.199.14";
$username_db = "crmcubic_presupas";
$password_db = "123q1q2q3Q";
$dbname = "crmcubic_presup";

// Crear conexión
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Supongamos que el usuario ya ha iniciado sesión y tenemos su ID de usuario en la sesión
$username = $_SESSION['username'];

// Consultar si el usuario es administrador
$sql = "SELECT admin1 FROM login_presup WHERE username = ?";
$stmt = $conn->prepare($sql);

// Verificar si la preparación de la consulta fue exitosa
if ($stmt === false) {
    die("Error en la consulta SQL: " . $conn->error);
}

$stmt->bind_param("s", $username);  // "s" porque es un string
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<?php
// Conexión a la base de datos
$servername = "162.214.199.14";
$username_db = "crmcubic_presupas";
$password_db = "123q1q2q3Q";
$dbname = "crmcubic_presup";

// Crear conexión
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Obtener lista de formularios
$sql_forms = "SELECT id_form, nombre_form FROM info_forms_presup";
$result_forms = $conn->query($sql_forms);

// Obtener el id_form seleccionado si existe
$form_id_selected = isset($_GET['form_id']) ? $_GET['form_id'] : '';

// Construir la consulta para obtener los casos
$sql_cases = "SELECT ic.id_caso, ic.username, ic.fecha, ifp.nombre_form 
              FROM info_casos_presup AS ic
              JOIN info_forms_presup AS ifp ON ic.id_form = ifp.id_form";

// Si se ha seleccionado un formulario, agregamos una cláusula WHERE
if (!empty($form_id_selected)) {
    $sql_cases .= " WHERE ic.id_form = ?";
}

// Preparar y ejecutar la consulta
$stmt_cases = $conn->prepare($sql_cases);

if (!empty($form_id_selected)) {
    // Si hay un filtro, enlazamos el parámetro
    $stmt_cases->bind_param("i", $form_id_selected);
}

$stmt_cases->execute();
$result_cases = $stmt_cases->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de los Casos</title>
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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

            <?php
                // Suponiendo que ya has ejecutado la consulta SQL y tienes el valor de $is_admin

                // Verificar si el usuario es administrador (admin1 = 1)
                if ($is_admin == 1) {
                    // Mostrar el elemento de navegación solo si es administrador
                    echo '
                    <div class="wrapper">
    <nav class="sidebar" id="sidebarMenu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="menu.php">
                    <i class="fas fa-plus-circle"></i> Ingresar Caso
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="datos.php">
                    <i class="fas fa-folder-open"></i> Datos de los Casos
                </a>
            </li>
                    <li class="nav-item">
                        <a class="nav-link" href="permisos.php">
                            <i class="fas fa-user-shield"></i> Gestión de Permisos
                        </a>
                    </li>
                            </ul>
    </nav>
                    ';
                }
            ?>

    <div class="content">
        <div class="container mt-1">
           <h1>Resumen Casos</h1>
        
            <div class="row">
                <div class="col-md-12">
<!-- Formulario para seleccionar el tipo de formulario -->
<div class="container mt-4">
    <form method="GET" action="">
        <div class="mb-3">
            <div class="row">
                <div class="col-4">
                    <label for="formFilter" class="form-label">Filtrar por Tipo de Formulario:</label>
                </div>
                <div class="col-8">
                    <select class="form-select" id="formFilter" name="form_id" onchange="this.form.submit()">
                        <option value="">Todos los Formularios</option>
                        <?php
                        if ($result_forms->num_rows > 0) {
                            while ($row_form = $result_forms->fetch_assoc()) {
                                // Verificar si este formulario está seleccionado actualmente
                                $selected = '';
                                if (isset($_GET['form_id']) && $_GET['form_id'] == $row_form['id_form']) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $row_form['id_form'] . '" ' . $selected . '>' . $row_form['nombre_form'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Campo de búsqueda activa -->
        <div class="mb-3">
        <div class="row">
            <div class="col-4">
                <label for="searchCase" class="form-label">Buscar por nombre de caso:</label>
            </div>
            <div class="col-8">
                <input type="text" id="searchCase" class="form-control" placeholder="Buscar por nombre..." onkeyup="filtrarCasos()">
            </div>
        </div>
    </div>

    </form>

   <!-- Mostrar los casos filtrados -->
    <div class="container" id="caseResults">
        <?php
        // Conexión a la base de datos
        $servername = "162.214.199.14";
        $username_db = "crmcubic_presupas";
        $password_db = "123q1q2q3Q";
        $dbname = "crmcubic_presup";

        // Crear conexión
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

// Consulta para obtener los casos junto con el nombre del formulario y demás detalles
$sql = "SELECT ic.id_caso, ic.username, ic.fecha, ifp.nombre_form, ic.id_form, 
               pf.cliente_direccion, pf.cliente_comuna, pf.cliente_nombre, pf.cliente_rut
        FROM info_casos_presup AS ic
        JOIN info_forms_presup AS ifp ON ic.id_form = ifp.id_form
        JOIN presp_formularios AS pf ON ic.id_caso = pf.id_caso";

$result_cases = $conn->query($sql);

// Verificar si hay resultados
if ($result_cases) {
    if ($result_cases->num_rows > 0) {
        // Iterar sobre los resultados y generar las tarjetas de los casos
        $borradorCounter = 1; // Contador para los borradores
        while ($row = $result_cases->fetch_assoc()) {
            // Obtener los valores o establecer valores predeterminados
            $direccion = !empty($row["cliente_direccion"]) ? $row["cliente_direccion"] : "Dirección No Registrada";
            $comuna = !empty($row["cliente_comuna"]) ? $row["cliente_comuna"] : "Comuna No Registrada";
            $nombre = !empty($row["cliente_nombre"]) ? $row["cliente_nombre"] : "Borrador " . $borradorCounter++;
            $rut = !empty($row["cliente_rut"]) ? $row["cliente_rut"] : "Rut No Registrado";

            $nombre_form = $row["nombre_form"];
            $id_form = $row["id_form"];
            $id_caso = $row["id_caso"];
            $username = $row["username"];
            $fecha = $row["fecha"];

            // Generar la tarjeta
            echo '
            <div class="card mb-3 caso-item">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="formulario-detalle">
                        <h5 class="card-title mb-0 caso-nombre">' . htmlspecialchars($nombre) . '</h5>
                        <p class="card-text text-muted mb-0 caso-nombre">Comuna: ' . htmlspecialchars($comuna) . '</p>
                        <p class="card-text text-muted mb-0 caso-nombre">Dirección: ' . htmlspecialchars($direccion) . '</p>
                        <p class="card-text text-muted mb-0 caso-nombre">Rut Cliente: ' . htmlspecialchars($rut) . '</p>
                        <p class="card-text text-muted mb-0">Formulario: ' . htmlspecialchars($nombre_form) . '</p>
                        <p class="card-text text-muted mb-0">Usuario: ' . htmlspecialchars($username) . '</p>
                        <p class="card-text text-muted mb-0">Fecha: ' . htmlspecialchars($fecha) . '</p>
                    </div>
                    <div>
                        <div class="container col-12">
                            <div class="col">
                                <a type="button" class="btn btn-primary mb-1 d-flex justify-content-center align-items-center" 
                                   style="min-height: 70px; min-width: 200px;" 
                                   href="form' . htmlspecialchars($id_form) . '.php?id_caso=' . htmlspecialchars($id_caso) . '&id_form=' . htmlspecialchars($id_form) . '">
                                   Editar Formulario
                                </a>
                            </div>';
                            if ($is_admin == 1) { 
                            echo '
                            <div class="col">
                                <a type="button" class="btn btn-warning mb-1 d-flex justify-content-center align-items-center" 
                                   style="min-height: 70px; min-width: 200px;" 
                                   href="pdf_caso_' . htmlspecialchars($id_form) . '.php?id_caso=' . htmlspecialchars($id_caso) . '">
                                   Ver Detalles
                                </a>
                            </div>
                            ';
                        }
                            echo '
                            <div class="col">
                                <a type="button" class="btn btn-danger mb-1 d-flex justify-content-center align-items-center" 
                                   style="min-height: 70px; min-width: 200px;" 
                                   href="pdf_presp_' . htmlspecialchars($id_form) . '.php?id_caso=' . htmlspecialchars($id_caso) . '">
                                   Descargar Presupuesto
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo '<p>No se encontraron casos ingresados.</p>';
    }
} else {
    // Mostrar error si la consulta falla
    echo 'Error en la consulta SQL: ' . $conn->error;
}

        ?>
    </div>
</div>

<?php
// Cerrar la conexión y la declaración
$stmt_cases->close();
$conn->close();
?>
        
                    <!-- Agrega más tarjetas de formularios detallados según sea necesario -->
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function filtrarCasos() {
    // Obtener el valor de búsqueda ingresado
    var input = document.getElementById('searchCase').value.toLowerCase();
    
    // Obtener todos los elementos con la clase 'caso-item' (cada tarjeta de caso)
    var casos = document.getElementsByClassName('caso-item');

    // Iterar sobre todos los casos y ocultar aquellos que no coinciden con la búsqueda
    for (var i = 0; i < casos.length; i++) {
        var casoNombre = casos[i].getElementsByClassName('caso-nombre')[0].innerText.toLowerCase();

        // Si el nombre del caso contiene el texto de búsqueda, lo mostramos, si no, lo ocultamos
        if (casoNombre.includes(input)) {
            casos[i].style.display = ''; // Mostrar
        } else {
            casos[i].style.display = 'none'; // Ocultar
        }
    }
}
</script>
</body>
</html>