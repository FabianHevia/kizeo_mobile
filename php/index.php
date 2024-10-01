<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $userEmail = trim($_POST['userEmail']);
    $password = $_POST['loginPassword'];

    // Preparar y ejecutar la consulta para encontrar el usuario de forma segura
    $sql = "SELECT * FROM login_presup WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userEmail, $userEmail); // El mismo parámetro para username o email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si existe un usuario con el nombre o email proporcionado
        $row = $result->fetch_assoc();

        // Verificar la contraseña hasheada
        if (password_verify($password, $row['password'])) {
            // La contraseña es correcta, se inicia la sesión
            $_SESSION['user_id'] = $row['user_id'];  // ID del usuario
            $_SESSION['username'] = $row['username']; // Nombre de usuario
            $_SESSION['admin'] = $row['admin']; // Rol de administrador o no

            // Redirigir al menú principal
            echo "<script>
                    alert('Inicio de sesión exitoso. Bienvenido, " . $row['username'] . "!');
                    window.location.href = 'datos.php';
                  </script>";
            exit(); // Finaliza el script después de redireccionar
        } else {
            // La contraseña es incorrecta
            echo "<script>alert('Contraseña incorrecta.');</script>";
        }
    } else {
        // No se encontró ningún usuario con ese nombre o email
        echo "<script>alert('El nombre de usuario o email no existe.');</script>";
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<!-- Section: Design Block -->
<section class="">
    <!-- Jumbotron -->
    <div class="px-4 py-5 px-md-5 text-center text-lg-start" style="background-color: hsl(0, 0%, 96%)">
      <div class="container">
        <div class="row gx-lg-5 align-items-center">
          <div class="col-lg-6 mb-5 mb-lg-0">
            <h1 class="my-5 display-3 fw-bold ls-tight">
              TITULO DE LA APP <br />
              <span class="text-primary">PRESUPUESTOS</span>
            </h1>
            <p style="color: hsl(217, 10%, 50.8%)">
                Inicie sesión para acceder a la APP de presupuestos.
            </p>
          </div>
  
          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="card">
              <div class="card-body py-5 px-md-5">
                <form id="loginForm" action="" method="POST">
                    <h4 class="mb-4">Inicia Sesion en tu cuenta</h4>
                    <!-- Usuario o Email -->
                    <div class="mb-3">
                        <div class="form-outline input-group">
                            <input type="text" id="userEmail" name="userEmail" class="form-control" placeholder="Nombre de Usuario o Email" required />
                        </div>
                    </div>
                  
                    <!-- Contraseña -->
                    <div class="mb-3">
                      <div class="form-outline input-group">
                        <input type="password" id="loginPassword" name="loginPassword" class="form-control" placeholder="Contraseña" required />
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('loginPassword')">
                          <i class="fas fa-eye"></i>
                        </button>
                      </div>
                    </div>

                    <p>¿No tienes una cuenta? <a style="font-weight: bold;" href="register.php">Registrarse</a></p>
                  
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mx-auto" style="max-width: 100%;">
                      Iniciar Sesión
                    </button>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Jumbotron -->
  </section>
  <!-- Section: Design Block -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Función para mostrar/ocultar la contraseña
    function togglePasswordVisibility(id) {
      const input = document.getElementById(id);
      const icon = input.nextElementSibling.querySelector('i');
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  
    // Validación de email o usuario
    const userEmailInput = document.getElementById('userEmail');
  
    document.getElementById('loginForm').addEventListener('submit', (event) => {
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      const isEmail = emailPattern.test(userEmailInput.value);
  
      if (!isEmail && userEmailInput.value.trim() === '') {
        event.preventDefault();
        alert('Por favor, ingresa un nombre de usuario o un email válido.');
      }
    });
  </script>
</body>
</html>
