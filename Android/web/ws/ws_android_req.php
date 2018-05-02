<?php
/*
 * desde aqui se recibiran los comandos desde android y se enviaran las correspondientes peticiones al webservice de la placa arduino.
 * tambien se devolveran consultas a la base de datos como los estados de los actuadores, o informacion de los sensores
 * utiliza los metodos del controlador ControlCasa.controller 
 *  
 * 
 * */

require_once '../src/private/Config.php';
require_once '../src/model/Conexion.model.php';
require_once '../src/model/Log.model.php';
require_once '../src/controller/Log.controller.php';
require_once '../src/controller/ControlCasa.controller.php';