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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
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
            <?php
                // Suponiendo que ya has ejecutado la consulta SQL y tienes el valor de $is_admin

                // Verificar si el usuario es administrador (admin1 = 1)
                if ($is_admin == 1) {
                    // Mostrar el elemento de navegación solo si es administrador
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="permisos.php">
                            <i class="fas fa-user-shield"></i> Gestión de Permisos
                        </a>
                    </li>
                    ';
                }
            ?>
        </ul>
    </nav>

    <div class="content">
        <div class="container mt-1">
            <div class="row mb-3">
                <div class="col-md-4">
                    <h2>Ingresar Nuevo Caso</h2>
                </div>
                <div class="col-md-8">
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

// Consulta para obtener los formularios
$sql = "SELECT id_form, nombre_form FROM info_forms_presup";
$result = $conn->query($sql);
?>

<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle w-100 mt-1 p-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        Seleccionar Tipo de Formulario a Rellenar
    </button>
    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
        <?php
        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            // Iterar sobre los resultados y generar las opciones del dropdown
            while ($row = $result->fetch_assoc()) {
                echo '<li><a class="dropdown-item" href="form' . $row['id_form'] . '.php">' . $row['nombre_form'] . '</a></li>';
            }
        } else {
            echo '<li><a class="dropdown-item" href="#">No se encontraron formularios</a></li>';
        }
        ?>
    </ul>
</div>

<?php
// Cerrar la conexión
$conn->close();
?>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-12">

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

// Consulta para obtener los formularios
$sql = "SELECT id_form, nombre_form, desc_form, fecha_form FROM info_forms_presup";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Iterar sobre los resultados
    while($row = $result->fetch_assoc()) {
        // Rellenar los datos en la estructura HTML
        echo '
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="formulario-detalle">
                    <h5 class="card-title mb-0">' . $row["nombre_form"] . '</h5>
                    <p class="card-text text-muted mb-0">' . $row["desc_form"] . '</p>
                    <p class="card-text text-muted mb-0"><small>Fecha: ' . $row["fecha_form"] . '</small></p>
                </div>
                <div>
                    <div class="container col-12">
                        <div class="col">
                            <a type="button" class="btn btn-primary mb-1 d-flex justify-content-center align-items-center" style="min-height: 70px; min-width: 200px;" href="form' . $row['id_form'] . '.php?id_form=1">Rellenar Formulario</a>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-warning mb-1" style="min-height: 70px; min-width: 200px;">Modificar Permisos</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-info" style="min-height: 70px; min-width: 200px;">Mostrar Registros</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<li><a class="dropdown-item" href="#">No se encontraron formularios</a></li>';
}
// Cerrar la conexión
$conn->close();
?>
        
                    <!-- Agrega más tarjetas de formularios detallados según sea necesario -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>