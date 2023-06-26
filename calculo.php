<?php
$servername = "us-cdbr-east-06.cleardb.net";
$username = "b0c75fe74aa810";
$password = "dc197b2a";
$dbname = "heroku_a1f1d7ba1837c0b";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $prestamo = $_POST["prestamo"];
     $cuota_inicial = $_POST["cuota_inicial"];
     $tea = $_POST["tea"];
     $plazo = $_POST["plazo"];
     $frecuencia = $_POST["frecuencia"];
  
    
//     switch ($frecuencia) {
//     case 'semestral':
//         $frec1 = 2;
//         break;
//     case 'anual':
//         $frec1 = 1;
//         break;
//     case 'quincenal':
//         $frec1 = 24;
//         break;
//     case 'mensual':
//         $frec1 = 12;
//         break;
//     case 'semanal':
//         $frec1 = 48;
//         break;
//     default:
//         echo "Valor de frecuencia desconocido: $frecuencia";

    $cuota_inicial = $prestamo * ($cuota_inicial / 100);
    $saldo_inicial = $prestamo - $cuota_inicial;
    $tes = pow(1 + ($tea / 100), 0.5) - 1;
    $total_cuotas = $plazo * $frecuencia;
    $cuota = ($saldo_inicial * $tes) / (1 - pow(1 + $tes, -$total_cuotas));

    $sql = "INSERT INTO prestamos (prestamo, cuota_inicial, tea, plazo, tes, total_cuotas, cuota)
    VALUES ($prestamo, $cuota_inicial, $tea, $plazo, $tes, $total_cuotas, $cuota)";

    if ($conn->query($sql) === TRUE) {
        $prestamo_id = $conn->insert_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $tabla = '';
    $saldo_actual = $saldo_inicial;
    for ($i = 1; $i <= $total_cuotas; $i++) {
        $interes = $saldo_actual * $tes;
        $amortizacion = $cuota - $interes;
        $saldo_final = $saldo_actual - $amortizacion;

        $sql = "INSERT INTO cuotas (prestamo_id, numero_de_cuota, saldo_inicial, interes, cuota, amortizacion, saldo_final)
        VALUES ($prestamo_id, $i, $saldo_actual, $interes, $cuota, $amortizacion, $saldo_final)";

        if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
if($i == $total_cuotas){
          $tabla .= "<tr>
                        <td>$i</td>
                        <td>$tea%</td>
                        <td>$tes%</td>
                        <td>S</td>
                        <td>$saldo_actual</td>
                        <td>$interes</td>
                        <td>$cuota</td>
                        <td>$amortizacion</td>
                        <td>0</td>
                   </tr>";

        $saldo_actual = $saldo_final;
        if ($i == $total_cuotas) {
            $saldo_final = 0;
            break;
        }
}
else{
      $tabla .= "<tr>
                        <td>$i</td>
                        <td>$tea%</td>
                        <td>$tes%</td>
                        <td>S</td>
                        <td>$saldo_actual</td>
                        <td>$interes</td>
                        <td>$cuota</td>
                        <td>$amortizacion</td>
                        <td>$saldo_final</td>
                   </tr>";

        $saldo_actual = $saldo_final;
        if ($i == $total_cuotas) {
            $saldo_final = 0;
            break;
        }
}

  
       
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calculadora de Préstamo - Resultado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <a href="calculo1.php" class="btn btn-primary mt-3">Atrás</a>
    <div class="container">
        <h1 class="mt-4 mb-3">Calculadora de Préstamo - Resultado</h1>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
            <h2>Plan de Pagos</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Número de Cuota</th>
                        <th>TEA</th>
                        <th>TES</th>
                        <th>Plazo Gracia</th>
                        <th>Saldo Inicial</th>
                        <th>Interés</th>
                        <th>Cuota</th>
                        <th>Amortización</th>
                        <th>Saldo Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $tabla; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="registros.php" class="btn btn-primary mt-3">Ver todos los préstamos</a>
        
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>
