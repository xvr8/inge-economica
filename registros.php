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

$sql = "SELECT * FROM prestamos";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calculadora de Préstamo - Registros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <a href="calculo.php" class="btn btn-primary mt-3">Regresar</a>
    <div class="container">
        <h1 class="mt-4 mb-3">Todos los préstamos</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Préstamo</th>
                    <th>Cuota Inicial</th>
                    <th>TEA</th>
                    <th>Plazo</th>
                    <th>TES</th>
                    <th>Total Cuotas</th>
                    <th>Cuota</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["prestamo"]. "</td><td>" . $row["cuota_inicial"]. "</td><td>"
                        . $row["tea"]. "</td><td>" . $row["plazo"]. "</td><td>" . $row["tes"]. "</td><td>"
                        . $row["total_cuotas"]. "</td><td>" . $row["cuota"]. "</td><td>" . $row["fecha"]. "</td>
                        <td><button class='btn btn-primary ver-detalles' data-id='" . $row["id"]. "'>Ver detalles</button></td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div id="detalles"></div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function(){
        $(".ver-detalles").click(function(){
            var id = $(this).data('id');
            $.ajax({url: "detalles.php?id=" + id, success: function(result){
                $("#detalles").html(result);
            }});
        });
    });
    </script>
</body>
</html>
