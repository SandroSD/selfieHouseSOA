<?php
/*
 * Aca se maneja la recepcion de eventos desencadenados por Arduino. Se actualiza la DB y se generan notificaciones a 
 * visualizar luego desde Android
 *
 * Este controlador debe recibir los parametros GET: "accion" y "disparador"
 * 
 * "accion"     -> corresponde a la acción a ejecutar sobre el actuador.
 * "disparador" -> es el evento que lo ocasionó.
 *
 * */
require_once '../src/private/Config.php';
require_once '../src/controller/Log.controller.php';
require_once '../src/model/Conexion.model.php';
require_once '../src/model/Log.model.php';
require_once '../src/model/ArduinoReceiverAPI.php';

$arduinoReceiverWS = new ArduinoReceiverAPI();
$arduinoReceiverWS->API();

