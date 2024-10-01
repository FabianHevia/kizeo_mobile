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

// Obtener el username
$username = $_POST['username'];

// Eliminar el usuario de la base de datos
$sql = "DELETE FROM login_presup WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->close();

$conn->close();
?>
