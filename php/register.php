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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Comprobar que las contraseñas coincidan
    if ($password !== $confirmPassword) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Verificar si el usuario ya existe
    $sql_check = "SELECT * FROM login_presup WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Si ya existe el usuario o el email
        echo "El nombre de usuario o el email ya están en uso.";
    } else {
        // Si no existe, insertar el nuevo usuario
        $password_hashed = password_hash($password, PASSWORD_BCRYPT); // Hasheado de la contraseña
        $sql_insert = "INSERT INTO login_presup (username, password, email) VALUES ('$username', '$password_hashed', '$email')";

        if ($conn->query($sql_insert) === TRUE) {
            echo "Registro exitoso. Puedes iniciar sesión ahora.";
            header("Location: index.php");
            exit(); // Siempre es buena práctica llamar a exit() después de un redireccionamiento
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Sidebar con Bootstrap</title>
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
                Este es el registro oficial de la APP para los presupuestos, por favor ingrese sus datos :)
            </p>
          </div>
  
          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="card">
              <div class="card-body py-5 px-md-5">
                <form id="registrationForm" action="" method="POST">
                    <div class="row">
                    <h4 class="mb-5">Por favor, ingrese sus datos</h4>
                      <!-- Nombre de Usuario -->
                      <div class="col-md-6 mb-3">
                        <div class="form-outline input-group">
                          <input type="text" id="username" name="username" class="form-control" placeholder="Nombre de Usuario" required />
                        </div>
                      </div>
                      <!-- Email -->
                      <div class="col-md-6 mb-3">
                        <div class="form-outline input-group">
                          <input type="email" id="email" name="email" class="form-control" placeholder="Email" required />
                          <small id="emailFeedback" class="form-text text-danger d-none">Por favor, introduce un email válido.</small>
                        </div>
                      </div>
                    </div>
                  
                    <!-- Contraseña -->
                    <div class="mb-3">
                      <div class="form-outline input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required />
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password')">
                          <i class="fas fa-eye"></i>
                        </button>
                      </div>
                    </div>
                  
                    <!-- Repetir Contraseña -->
                    <div class="mb-4">
                      <div class="form-outline input-group">
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Repita la Contraseña" required />
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('confirmPassword')">
                          <i class="fas fa-eye"></i>
                        </button>
                        <small id="passwordFeedback" class="form-text text-danger d-none w-100">Las contraseñas no coinciden.</small>
                      </div>
                    </div>

                    <p>¿Ya tienes una cuenta? <a style="font-weight: bold;" href="index.php">Iniciar Sesion</a></p>
                  
                    <!-- Submit button -->
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mx-auto mb-4" style="max-width: 150%;">
                      Registrarse
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
  
    // Validación de las contraseñas
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordFeedback = document.getElementById('passwordFeedback');
  
    confirmPasswordInput.addEventListener('input', () => {
      if (passwordInput.value !== confirmPasswordInput.value) {
        passwordFeedback.classList.remove('d-none');
      } else {
        passwordFeedback.classList.add('d-none');
      }
    });
  
    // Validación de email
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('emailFeedback');
  
    emailInput.addEventListener('input', () => {
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      if (!emailPattern.test(emailInput.value)) {
        emailFeedback.classList.remove('d-none');
      } else {
        emailFeedback.classList.add('d-none');
      }
    });
  
    // Validar formulario al enviar
    document.getElementById('registrationForm').addEventListener('submit', (event) => {
      if (passwordInput.value !== confirmPasswordInput.value) {
        event.preventDefault();
        passwordFeedback.classList.remove('d-none');
      }
    });
  </script>
</body>
</html>
