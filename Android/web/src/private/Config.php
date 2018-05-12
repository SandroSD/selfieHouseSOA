<?php

/***************************************
 ******** LOG ******
 ***************************************/

# Directorio del log

define("PATH_LOG","C:/xampp/htdocs/selfieHouse/log/");
//define("PATH_LOG","log/");

# TamaÃ±o del log (Cantidad de lineas visualizables en LogView)

define("LOG_SIZE","50000");


#  Nombre de archivos log, (deben existir)

define("LOG_ALERTA","alerta");
define("LOG_SERVER","server");
define("LOG_DB","database");
define("LOG_CLIENTE","shouse");


# ConfiguraciÃ³n de sesiÃ³n


define('CANT_INTENTOS_LOGUEO',3);



/***************************************
 ******** VALORES GET ****************
 ***************************************/


#  Menues y Opciones

define("SECCION_CONTROL",          sha1("2ECcEbS4zA"));
    define("MENU_CONTROLAR",       sha1("oJdN22MGuu"));
    define("MENU_ALERTAS",         sha1("29IHEus6sW"));
    
define("SECCION_CONSULTA",          sha1("unND8C1En3"));
    define("MENU_AREAS",            sha1("JMENmHiY2X"));
    define("MENU_CONTROLES",        sha1("UbKClZnGPB"));

define("SECCION_ADMINISTRACION",    sha1("rOm0Ysw1j8"));
    define("MENU_ENTIDADES",        sha1("KuLqH87RW3"));
   
        define("SUBMENU_ESTABLECIMIENTOS", sha1("TCwEOWtSqF"));
        define("SUBMENU_SECTORES",       sha1("aQ0Gjo3Oy6"));
        define("SUBMENU_AREAS",       sha1("1mDILErcDp"));
    
    define("MENU_DISPOSITIVOS",       sha1("qesPPZ0Zqo"));
        define("SUBMENU_SENSORES",       sha1("vH6xGm10Cm"));
        define("SUBMENU_CALIBRACION",       sha1("HSeRpNptJk"));
        define("SUBMENU_NODOS",       sha1("PsmlA789At"));
        
    define("MENU_OPCIONES",       sha1("ESwFMBCV9M"));
        define("SUBMENU_OPCIONES",       sha1("YJQqzhD69P"));
        define("SUBMENU_USUARIOS",       sha1("aSDQa2269P"));
        define("SUBMENU_PERFILES",       sha1("YasDAShD69P"));

define("SECCION_LOGOUT",    sha1("ySSDFsdQ41"));
    define("MENU_NOTIFICACIONES", sha1("U6snHmmplj"));
    define("MENU_NOTICIAS", sha1("bw4nIUz0Cq"));

/***************************************
 ******** BASE DE DATOS ****************
 ***************************************/

# Configuracion de la base de datos MySQL

define("DIRECCION_ARDUINO","localhost");
define("SERVER","localhost");
define("USER","root");
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
define("ID_LED_AMARILLO",4);
define("ID_LED_AZUL",5);
define("ID_LED_ROJO",6);
define("ID_LED_VERDE",7);


/***************************************
 ******** ESTADOS DE ACTUADORES ****************
 ***************************************/
define("PUERTA_TRABADA",1000);
define("PUERTA_DESTRABADA",1001);
define("BUZZER_ACTIVADO",1002);
define("BUZZER_DESACTIVADO",1003);
define("VENTILADOR_ACTIVADO",1004);
define("VENTILADOR_DESACTIVADO",1005);

/***************************************
 ******** ESTADOS DE DISPARADORES ****************
 ***************************************/

define("DISPARADOR_MOVIMIENTO",2000);
define("DISPARADOR_LLAMA",2001);
define("DISPARADOR_TEMPERATURA",2002);
define("DISPARADOR_LUZ",2003);
define("DISPARADOR_MANUAL",2003);
