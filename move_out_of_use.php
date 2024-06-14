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
$removalDate = date('Y-m-d'); // Fecha actual
$removalReason = 'Obsoleto'; // Puedes cambiar esta razón o hacerla dinámica

$sql = "CALL MoveInstrumentOutOfUse('$id', '$removalDate', '$removalReason')";

if ($conn->query($sql) === TRUE) {
    echo "Instrumento movido a fuera de uso exitosamente.";
    header('Location: admin.php'); // Redirige de vuelta a la página de administración
} else {
    echo "Error al mover el instrumento: " . $conn->error;
}

$conn->close();
?>
