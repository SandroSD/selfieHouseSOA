<?php
require_once '../private/Config.php';
require_once '../model/Conexion.model.php';

if(isset($_POST['op'])){
	$solicitud = $_POST['op'];
	
	if(isset($_POST['btnAceptar'])){
		$nuevoEstado= 0;
	} else if (isset($_POST['btnRechazar'])){
		$nuevoEstado= 2;
	}	
	if(Conexion::cambiarEstadoSolicitud($solicitud,$nuevoEstado)){
		if($nuevoEstado == 2){
			$mensaje = "<br><br><br><font color=red>Solicitud rechazada.</font>";
		} else {
			$mensaje = "<br><br><br><font color=blue>Acceso concedido. Puerta destrabada.</font>";
		}		
	} else {
		$mensaje = "<br><br><br>Hubo un error al realizar la acción solicitada";
	}	
	
} else {
	$conn = new mysqli(SERVER,USER,PASS,DB);
	$idPersona = $_GET['op'];
	$query = "SELECT * FROM acceso_solicitud WHERE id=$idPersona";
	$resultado = $conn->query($query)->fetch_assoc();   
	$ruta = "../../".$resultado['foto'];	
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/solicitarAcceso.css"/>

    <!--script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script-->
    <script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/verPerfil.js"></script>
    <title>Solicitar Acceso</title>
</head>
<body>
<div class="container-fluid">
    <?php
	if(isset($_POST['op'])){
	?>
	<div class="row">
        <div class='col-12 col-md-5 col-lg-5 offset-md-3 offset-lg-3'>            
            <h2 align=center><?php echo $mensaje;?></h2>
        </div>
    </div> 
	
	<?php
	} else {
	?>
	
	<div class="row">
        <div class='col-12 col-md-5 col-lg-5 offset-md-3 offset-lg-3'>            
            <form method="POST" action="">
				<div id="divFoto">
					<span>Esta persona solicita acceso a la casa</span>
					<img src="<?php echo $ruta; ?>" id="foto">
					<div class="col-md-5 offset-md-4">
						<input type=submit class="btn btn-success" name="btnAceptar" value="Aceptar"></input>
						<input type=submit class="btn btn-danger" name="btnRechazar" value="Rechazar"></input>
						<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
					</div>                
				</div> 
			</form>
        </div>
    </div> 
	<?php
	}
	?>	
</div>    
</body>
</html>