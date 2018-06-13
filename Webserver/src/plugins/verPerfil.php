<?php
require_once '../private/Config.php';

$conn = new mysqli(SERVER,USER,PASS,DB);

$idPersona = $_GET['op'];

$query = "SELECT * FROM acceso WHERE id=$idPersona";

$resultado = $conn->query($query)->fetch_assoc();

$valores = explode('/',$resultado['FOTO']);

$ruta = "../".$valores[2]."/".$valores[3];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
    <link rel="stylesheet" href="../assets/css/solicitarAcceso.css">

    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="../assets/js/verPerfil.js"></script>
    <title>Solicitar Acceso</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class='col-12 col-md-5 col-lg-5 offset-md-3 offset-lg-3'>            
            <div id="divFoto">
                <span>Esta persona solicita acceso a la casa</span>
                <img src="<?php echo $ruta; ?>" id="foto">
                <div class="col-md-5 offset-md-4">
                    <button class="btn btn-success" id="btnAceptar" onClick="cambiarEstado(<?php echo $_GET['op'];?>)">Aceptar</button>
                    <button class="btn btn-danger" id="btnRechazar" onClick="rechazar(<?php echo $_GET['op'];?>)">Rechazar</button>
                </div>                
            </div>              
        </div>
    </div>    
</div>    
</body>
</html>