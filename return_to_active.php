<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

require 'config.php';
$conn = getConnection('admin');

if (!isset($_GET['id'])) {
    echo "ID del instrumento no especificado.";
    exit();
}

$id = $_GET['id'];

$sql = "CALL ReturnInstrumentToActive('$id')";

if ($conn->query($sql) === TRUE) {
    echo "Instrumento regresado a activos exitosamente.";
    header('Location: out_of_use.php'); // Redirige de vuelta a la página de instrumentos fuera de uso
} else {
    echo "Error al regresar el instrumento: " . $conn->error;
}

$conn->close();
?>
