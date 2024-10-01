<?php
session_start();

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

// Obtener datos enviados vía POST
$username = $_POST['username'];
$role = $_POST['role']; // Puede ser 'admin', 'evaluador' o 'inspector'
$value = $_POST['value']; // Puede ser 0 o 1

// Actualizar el campo correspondiente en la base de datos
$sql = "";
if ($role == 'admin1') {
    $sql = "UPDATE login_presup SET admin1 = ? WHERE username = ?";
} elseif ($role == 'evaluador') {
    $sql = "UPDATE login_presup SET evaluador = ? WHERE username = ?";
} elseif ($role == 'inspector') {
    $sql = "UPDATE login_presup SET inspector = ? WHERE username = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $value, $username); // "is" porque $value es entero y $username es string
$stmt->execute();
$stmt->close();

$conn->close();
?>
