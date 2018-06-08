package com.selfiehouse.selfiehouse.Clases;

public interface Constantes {

    /* Datos de red */
    public static final String IP_APACHE = "192.168.3.189";
    public static final String PUERTO_APACHE = "8080";

    /* Actuadores */
    public static final int CANTIDAD_ESTADOS = 7;
    public static final int ID_TRABA = 1;
    public static final int ID_BUZZER = 2;
    public static final int ID_VENTILADOR = 3;
    public static final int ID_LED_ROJO = 4;
    public static final int ID_LED_VERDE = 5;
    public static final int ID_SELFIEHOUSE = 6;
    public static final int ID_DEBUG = 7;

    public static final int PUERTA_TRABADA = 1000;
    public static final int PUERTA_DESTRABADA = 1001;
    public static final int BUZZER_ACTIVADO = 1002;
    public static final int BUZZER_DESACTIVADO = 1003;
    public static final int VENTILADOR_ACTIVADO = 1004;
    public static final int VENTILADOR_DESACTIVADO = 1005;
    public static final int SELFIEHOUSE_ACTIVADO = 1006;
    public static final int SELFIEHOUSE_DESACTIVADO = 1007;
    public static final int DEBUG_ACTIVADO = 1008;
    public static final int DEBUG_DESACTIVADO = 1009;
    public static final int NUEVA_FOTO = 1010;
    public static final int LED_ROJO_ENCENDIDO = 1011;
    public static final int LED_ROJO_APAGADO = 1012;
    public static final int LED_VERDE_ENCENDIDO = 1013;
    public static final int LED_VERDE_APAGADO = 1014;
    public static final int REINICIO = 9999;

    public static final int DISPARADOR_MANUAL = 2003;

    public static final int ACTIVADO = 1;
    public static final int DESACTIVADO = 0;

    public static final String RESPUESTA_404 = "Error en respuesta del servidor";
    public static final String RESPUESTA_ERROR_ACCION = "Hubo un error al completar la acción";
}
