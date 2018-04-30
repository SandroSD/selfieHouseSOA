<?php

/***************************************
 ******** LOG ******
 ***************************************/

# Directorio del log

define("PATH_LOG","C:/xampp/htdocs/".NOMBRE_DIRECTORIO."/log/");
//define("PATH_LOG","log/");

# Tamaño del log (Cantidad de lineas visualizables en LogView)

define("LOG_SIZE","50000");


#  Nombre de archivos log, (deben existir)

define("LOG_ALERTA","alerta");
define("LOG_SERVER","server");
define("LOG_DB","database");
define("LOG_CLIENTE","btmp");


# Configuración de sesión


define('CANT_INTENTOS_LOGUEO',3);


# Configuración de tiempo de sesión (en ms.)


define('TIEMPO_SESION',150000);

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

define("SERVER","localhost");
define("USER","sh_admin");
define("PASS","asdasdasdasd");
define("DB","db_selfieHouse");

/***************************************
 ******** ESTADOS DE USUARIO ****************
 ***************************************/
define("USUARIO_ACTIVO","1");
define("USUARIO_INACTIVO","2");
define("USUARIO_BLOQUEADO","-1");

# Resultados programados

define("TODO_OK",1);
define("ERROR",2);
define("USUARIO_EXISTENTE",3);
define("USUARIO_INEXISTENTE",4);
define("USUARIO_ERROR_BORRAR_A_SI_MISMO",5);
define("USUARIO_DATOS_INCORRECTOS",6);
define("USUARIO_BLOQUEADO",-1);
define("USUARIO_ELIMINADO",9);

define("PERFIL_EXISTENTE",3);
define("PERFIL_INEXISTENTE",4);
define("PERFIL_ELIMINADO",9);

define("ACTIVAR_ALARMA",1);
define("DESACTIVAR_ALARMA",0);