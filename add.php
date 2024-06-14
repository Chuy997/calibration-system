<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

require 'config.php';
$conn = getConnection('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['ID'];
    $description = $_POST['Description'];
    $brand = $_POST['Brand'];
    $model = $_POST['Model'];
    $serialNumber = $_POST['SerialNumber'];
    $calDate = $_POST['CalDate'];
    $dueDate = $_POST['DueDate'];
    $certificateNo = $_POST['CertificateNo'];
    $comments = $_POST['Comments'];

    $sql = "INSERT INTO Instruments (ID, Description, Brand, Model, SerialNumber, CalDate, DueDate, CertificateNo, Comments)
            VALUES ('$id', '$description', '$brand', '$model', '$serialNumber', '$calDate', '$dueDate', '$certificateNo', '$comments')";

    if ($conn->query($sql) === TRUE) {
        echo "Instrumento agregado exitosamente.";
    } else {
        echo "Error al agregar el instrumento: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Nuevo Instrumento</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h1>Agregar Nuevo Instrumento</h1>
        <form action="add.php" method="post">
            <div class="form-group">
                <label for="ID">ID:</label>
                <input type="text" name="ID" required>
            </div>
            <div class="form-group">
                <label for="Description">Descripción:</label>
                <input type="text" name="Description" required>
            </div>
            <div class="form-group">
                <label for="Brand">Marca:</label>
                <input type="text" name="Brand" required>
            </div>
            <div class="form-group">
                <label for="Model">Modelo:</label>
                <input type="text" name="Model" required>
            </div>
            <div class="form-group">
                <label for="SerialNumber">Número de Serie:</label>
                <input type="text" name="SerialNumber" required>
            </div>
            <div class="form-group">
                <label for="CalDate">Fecha de Calibración:</label>
                <input type="date" name="CalDate" required>
            </div>
            <div class="form-group">
                <label for="DueDate">Fecha de Vencimiento:</label>
                <input type="date" name="DueDate" required>
            </div>
            <div class="form-group">
                <label for="CertificateNo">Número de Certificado:</label>
                <input type="text" name="CertificateNo" required>
            </div>
            <div class="form-group full-width">
                <label for="Comments">Comentarios:</label>
                <textarea name="Comments"></textarea>
            </div>
            <input type="submit" value="Agregar">
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
