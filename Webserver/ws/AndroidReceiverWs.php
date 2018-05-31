<?php
/*
 * Aca se maneja la recepcion, almacenamiento y envio de notificaciones de solicitud de acceso
 *
 * Este controlador debe recibir los parametros GET: "accion" y "disparador"
 *
 * "accion"     -> corresponde a la acción a ejecutar sobre el embebido.
 * "disparador" -> es el evento que lo ocasionó.
 * "foto"       -> es la ruta donde se guardo la foto en el servidor (opcional)
 *
 * /// Geolocalizacion JS - https://www.w3schools.com/Html/html5_geolocation.asp
 *
 * */
require_once '../src/private/Config.php';
require_once '../src/controller/Log.controller.php';
require_once '../src/model/Conexion.model.php';
require_once '../src/model/Log.model.php';
require_once '../src/model/AndroidReceiverAPI.php';

$androidReceiverWS = new AndroidReceiverAPI();
$androidReceiverWS->API();

