<?php
/***************************************
 ******** DISPOSITIVOS ******
 ***************************************/

define("IP_ARDUINO","192.168.1.200");
define("IP_ANDROID","192.168.1.201");   // Creo que no hace falta

/***************************************
 ******** LOG ******
 ***************************************/

# Directorio del log
define("NOMBRE_DIRECTORIO","selfieHouse");
define("PATH_LOG","C:/xampp/htdocs/".NOMBRE_DIRECTORIO."/log/");
# Tamaño del log (Cantidad de lineas visualizables en LogView)
define("LOG_SIZE","50000");
#  Nombre de archivos log, (deben existir)
define("LOG_ALERTA","alerta");
define("LOG_SERVER","server");
define("LOG_DB","database");
define("LOG_CLIENTE","shouse");
# Configuracion de sesion
define('CANT_INTENTOS_LOGUEO',3);

/***************************************
 ******** BASE DE DATOS ****************
 ***************************************/
# Configuracion de la base de datos MySQL
define("DIRECCION_ARDUINO","localhost");
define("SERVER","localhost");
define("USER","selfiehouse");
define("PASS","selfiehousepass");
define("DB","db_selfiehouse");

/***************************************
 ******** ESTADOS  ****************
 ***************************************/
define("ACTIVADO","1");
define("DESACTIVADO","0");

define("USUARIO_ACTIVO","1");
define("USUARIO_INACTIVO","2");
define("USUARIO_BLOQUEADO","-1");

# Resultados programados

define("TODO_OK",1);
define("ERROR",0);

/***************************************
 ******** ID COMPONENTES  ****************
 ***************************************/

define("ID_TRABA",1);
define("ID_BUZZER",2);
define("ID_VENTILADOR",3);
define("ID_LED_ROJO",4);
define("ID_LED_VERDE",5);
define("ID_SELFIEHOUSE",6);
define("ID_DEBUG",7);


/***************************************
 ******** ESTADOS DE ACTUADORES ****************
 ***************************************/
define("PUERTA_TRABADA",1000);
define("PUERTA_DESTRABADA",1001);
define("BUZZER_ACTIVADO",1002);
define("BUZZER_DESACTIVADO",1003);
define("VENTILADOR_ACTIVADO",1004);
define("VENTILADOR_DESACTIVADO",1005);
define("SELFIEHOUSE_ACTIVADO",1006);
define("SELFIEHOUSE_DESACTIVADO",1007);
define("DEBUG_ACTIVADO",1008);
define("DEBUG_DESACTIVADO",1009);
define("NUEVA_FOTO",1010);
define("REINICIO_ESTADOS",9999);

/***************************************
 ******** ESTADOS DE DISPARADORES ****************
 ***************************************/

define("DISPARADOR_MOVIMIENTO",2000);
define("DISPARADOR_LLAMA",2001);
define("DISPARADOR_TEMPERATURA",2002);
define("DISPARADOR_LUZ",2003);
define("DISPARADOR_MANUAL",2004);
define("DISPARADOR_AUTOMATICO",2005);

/***************************************
 ******** TIPOS DE ACCESO ****************
 ***************************************/
define("ACCESO_SIMPLE",222);
define("ACCESO_ADMIN",777);
