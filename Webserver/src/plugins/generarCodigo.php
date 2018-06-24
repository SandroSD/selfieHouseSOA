<?php
require_once "../private/Config.php";
require_once "../model/Conexion.model.php";

if(isset($_POST["simple"]) || isset($_POST["admin"])){
	
	if(isset($_POST["simple"])){
		$tipo = $_POST['simple'];
	} else if (isset($_POST["admin"])){
		$tipo = $_POST['admin'];
	}
	do {
	 $codigo = Conexion::number_pad(rand(0,999999),6);
	} while(!Conexion::verificarCodigoExistente($codigo));
	 if(Conexion::insertarCodigoAcceso($codigo,$tipo)){
				        
	} else {
		Conexion::agregarAlLog(2,"AndroidReceiverAPI:: Error al insertar un codigo de acceso nuevo");
		echo "Error";
	}
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
	<title>Generar código de acceso</title>
</head>
<body>
<div class="container-fluid">
    <?php
	if(isset($codigo)){
	?>
		<br>
		<h2 align=center>Código generado</h2>
		<h1 align=center><font color=red><?php echo $codigo;?></font></h1>
	<?php	
	} else {
	?>
	<br><br><br><br><br>
	<div class="row">
        <div class='col-12 col-md-5 col-lg-5 offset-md-6 offset-lg-3'>            
           <form action="" method="POST">
				<div id="divAcceso">
					<div class="col-md-5 offset-md-4">
						<button class="btn btn-info" name="simple" value="222">Simple</button>
						<button class="btn btn-warning" name="admin" value="777">Admin</button>
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