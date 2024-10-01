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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Permisos</title>
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
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .content {
            margin-left: 250px; /* mismo ancho que el sidebar */
            padding: 20px;
            width: calc(100% - 250px);
        }
        @media (max-width: 768px) {
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
                display: none;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
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
            <li class="nav-item">
                <a class="nav-link" href="permisos.php">
                    <i class="fas fa-user-shield"></i> Gestión de Permisos
                </a>
            </li>
        </ul>
    </nav>

    <!-- Contenido -->
<div class="content">
<div class="container mt-4">
    <h1>Gestión de Permisos de Usuarios</h1>

    <!-- Tabla de Usuarios -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Evaluador</th>
                    <th>Inspector</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Consultar los usuarios
$sql = "SELECT username, email, admin1, evaluador, inspector FROM login_presup";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Iterar sobre cada fila de usuario
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['username']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';

        echo '<td>';
        echo '<input type="checkbox" data-role="admin1" data-username="' . $row['admin1'] . '" onclick="updateRole(this)"' . ($row['admin1'] == 1 ? ' checked' : '') . '> Admin';
        echo '</td>';
        echo '<td>';
        echo '<input type="checkbox" data-role="evaluador" data-username="' . $row['username'] . '" onclick="updateRole(this)"' . ($row['evaluador'] == 1 ? ' checked' : '') . '> Evaluador';
        echo '</td>';
        echo '<td>';
        echo '<input type="checkbox" data-role="inspector" data-username="' . $row['username'] . '" onclick="updateRole(this)"' . ($row['inspector'] == 1 ? ' checked' : '') . '> Inspector';
        echo '</td>';

        echo '</td>';
        echo '<td>';
        echo '<button class="btn btn-success btn-sm" onclick="saveChanges(\'' . $row['username'] . '\')"><i class="fas fa-save"></i> Guardar</button>';
        echo '<button class="btn btn-danger btn-sm" onclick="deleteUser(\'' . $row['username'] . '\')"><i class="fas fa-trash"></i> Eliminar</button>';
        echo '</td>';
        echo '</tr>';
    }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'No se encontraron usuarios.';
        }
        ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Modal para editar permisos -->
<div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPermissionsModalLabel">Editar Permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de permisos -->
                <form>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="permiso1">
                        <label class="form-check-label" for="permiso1">
                            Permiso 1
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="permiso2">
                        <label class="form-check-label" for="permiso2">
                            Permiso 2
                        </label>
                    </div>
                    <!-- Más permisos -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
function updateRole(element) {
    var username = element.getAttribute('data-username');
    var role = element.getAttribute('data-role');
    var value;

    // Verificar si es un select o un checkbox
    if (element.type === 'select-one') {
        value = element.value; // Admin o Usuario (0 o 1)
    } else if (element.type === 'checkbox') {
        value = element.checked ? 1 : 0; // Evaluador o Inspector (0 o 1)
    }

    // Enviar los cambios al servidor vía AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_rol.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Rol actualizado exitosamente.');
        } else {
            console.log('Error al actualizar el rol.');
        }
    };
    
    var params = 'username=' + username + '&role=' + role + '&value=' + value;
    xhr.send(params);
}

function saveChanges(username) {
    alert('Cambios guardados para ' + username);
}

function deleteUser(username) {
    if (confirm('¿Estás seguro de que quieres eliminar a ' + username + '?')) {
        // Enviar solicitud para eliminar al usuario
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_user.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Usuario eliminado.');
                location.reload(); // Recargar la página para reflejar los cambios
            } else {
                alert('Error al eliminar el usuario.');
            }
        };
        xhr.send('username=' + username);
    }
}
</script>
</body>
</html>

<?php
$conn->close();
?>