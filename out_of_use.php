<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

require 'config.php';
$conn = getConnection('consulta'); // Usamos consulta o admin dependiendo de los permisos que quieras dar

$sql = "SELECT * FROM Instruments_Out_Of_Use";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instrumentos Fuera de Uso</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h1>Instrumentos Fuera de Uso</h1>
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por cualquier campo">
        <table id="instrumentsTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">ID</th>
                    <th onclick="sortTable(1)">Description</th>
                    <th onclick="sortTable(2)">Brand</th>
                    <th onclick="sortTable(3)">Model</th>
                    <th onclick="sortTable(4)">Serial Number</th>
                    <th onclick="sortTable(5)">Cal Date</th>
                    <th onclick="sortTable(6)">Due Date</th>
                    <th onclick="sortTable(7)">Certificate No.</th>
                    <th onclick="sortTable(8)">Date Removed</th>
                    <th onclick="sortTable(9)">Reason for Removal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Description']); ?></td>
                    <td><?php echo htmlspecialchars($row['Brand']); ?></td>
                    <td><?php echo htmlspecialchars($row['Model']); ?></td>
                    <td><?php echo htmlspecialchars($row['SerialNumber']); ?></td>
                    <td><?php echo htmlspecialchars($row['CalDate']); ?></td>
                    <td><?php echo htmlspecialchars($row['DueDate']); ?></td>
                    <td><?php echo htmlspecialchars($row['CertificateNo']); ?></td>
                    <td><?php echo htmlspecialchars($row['DateRemoved']); ?></td>
                    <td>
                        <form action="update_reason.php" method="post">
                            <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                            <select name="ReasonForRemoval" onchange="this.form.submit()">
                                <option value="Obsoleto" <?php if ($row['ReasonForRemoval'] == 'Obsoleto') echo 'selected'; ?>>Obsoleto</option>
                                <option value="Fuera de calibración" <?php if ($row['ReasonForRemoval'] == 'Fuera de calibración') echo 'selected'; ?>>Fuera de calibración</option>
                                <option value="No funciona" <?php if ($row['ReasonForRemoval'] == 'No funciona') echo 'selected'; ?>>No funciona</option>
                            </select>
                        </form>
                    </td>
                    <td class="actions">
                        <a href="return_to_active.php?id=<?php echo $row['ID']; ?>">Regresar a Activos</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("instrumentsTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchCount = 0;
            table = document.getElementById("instrumentsTable");
            switching = true;
            dir = "asc"; 
            
            while (switching) {
                switching = false;
                rows = table.rows;
                
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                    
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchCount++;
                } else {
                    if (switchCount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>
