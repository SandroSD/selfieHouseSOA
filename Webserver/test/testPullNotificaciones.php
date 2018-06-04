<html>
	<form action="#" method="POST">
		
		<input name="pull_notificaciones" type="submit" value="true"> </input>
	</form>

</html>
<?php
require_once "../src/private/Config.php";
require_once "../src/model/Conexion.model.php";
require_once "../src/model/Log.model.php";
require_once "../src/controller/Log.controller.php";
require_once "../src/model/AndroidReceiverAPI.php";

if(isset($_POST)){
	
	$androidReceiverWS = new AndroidReceiverAPI();
	$androidReceiverWS->API();
	
}

