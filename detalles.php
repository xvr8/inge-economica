<?php
include 'navbar.php';
$servername = "us-cdbr-east-06.cleardb.net";
$username = "b0c75fe74aa810";
$password = "dc197b2a";
$dbname = "heroku_a1f1d7ba1837c0b";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$prestamo_id = $_GET['id'];
$sql = "SELECT * FROM cuotas WHERE prestamo_id = $prestamo_id";
$result = $conn->query($sql);

$conn->close();
?>
<a href="registros.php" class="btn btn-primary mt-3">Regresar</a>
<h2>Detalles del Préstamo <?php echo $prestamo_id; ?></h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Número de Cuota</th>
            <th>Saldo Inicial</th>
            <th>Interés</th>
            <th>Cuota</th>
            <th>Amortización</th>
            <th>Saldo Final</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["numero_de_cuota"]. "</td><td>" . $row["saldo_inicial"]. "</td><td>" 
                . $row["interes"]. "</td><td>" . $row["cuota"]. "</td><td>" . $row["amortizacion"]. "</td><td>" 
                . $row["saldo_final"]. "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay detalles para este préstamo</td></tr>";
        }
        ?>
    </tbody>
</table>
