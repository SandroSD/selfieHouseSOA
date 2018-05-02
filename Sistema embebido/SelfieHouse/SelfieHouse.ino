/****************************************************************************
|--------------------------------------------------------------------------
| Proyecto      : selfieHouse
| Version       : 0.0.1
| Actualizado   : 30/04/2018
| Bibliotecas   : Servo, DHT, ESP8266WebServer, ESP8266WiFiMulti, ESP8266mDNS
| Autores       : ~ Dezerio, Sandro (@SandroSD)
|                 ~ Jalid, Fernando (@fernandodj)
|                 ~ Ibaceta, Leandro (@libaceta)
|                 ~ Nestrojil, Lucas (@lucasnestrojil)
|                 ~ Trotta, Mauro. (@mauroat)
|--------------------------------------------------------------------------
|
| Casa inteligente ATR
|
*****************************************************************************/


/* Librerias */
#include <DHT.h>
#include <ESP8266WebServer.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266mDNS.h>
#include <Servo.h> 

/* Constantes */
#define DHTTYPE DHT22

#define CANTIDAD_INTENTOS_CONEXION 3
#define TIMEOUT_CONEXION 10
#define TIEMPO_PARPADEO 400       // ms
#define ACTIVADO 1
#define DESACTIVADO 0

#define TOPE_LLAMA  1
#define TOPE_TEMPERATURA	14 	
#define TOPE_HUMEDAD	85

#define PUERTA_TRABADA     1000
#define PUERTA_DESTRABADA   1001
#define BUZZER_ACTIVADO     1002
#define BUZZER_DESACTIVADO  1003
#define VENTILADOR_ACTIVADO     1004
#define VENTILADOR_DESACTIVADO  1005

#define DISPARADOR_MOVIMIENTO   2000 
#define DISPARADOR_LLAMA        2001
#define DISPARADOR_TEMPERATURA  2002
#define DISPARADOR_LUZ          2003
#define DISPARADOR_MANUAL       2004


/* Pines digitales */
int pinSensorTempyHum = 10;        // GPIO10 - SD03
int pinSensorMovimiento = 4;      // GPIO09 - SD02
int pinVentilador = 15;            // GPIO15 - D8
int pinServo = 16;                // GPIO16 - D0
int pinBuzzer = 3;                // GPIO03 - RX
int pinLEDAzul = 5;               // GPIO05 - D1
int pinLEDAmarillo = 4;           // GPIO04 - D2
int pinLEDRojo;
int pinLEDVerde;

/* Pines analogicos */
int pinSensorLlama = A0;          // A0

/* Sensores tipo objeto */
DHT sensorTempyHum(pinSensorTempyHum,DHTTYPE);
Servo servoTrabaPuerta;

/* Mediciones */
float medicionTemperatura, medicionHumedad, medicionSensacionTermica, medicionLlama;
int medicionMovimiento;

/* Flags y Semaforos*/
int estadoBuzzer, estadoTraba, estadoVentilador, estadoWebserver, estadoLlama, estadoTyH, estadoMovimiento;  


/* Conexion de red */
ESP8266WebServer server(80);    // Webserver
ESP8266WiFiMulti WiFiMulti;     // Responder de peticiones
WiFiClient client;              // Cliente que avisa al servidor Apache

int timeoutConexion = 10 ;      // 5 segundos para conectarse al Wifi
const char* ssid = "WIFi-MT";
const char* password = "*xeneize1905+";
const char * ipServidorApache = "192.168.3.186";              // Servidor Apache - Hay que disponer de una IP fija
const uint16_t puertoIpServidorApache = 80;                         // Puerto Servidor Apache

 /***************************************************************************
 *            FUNCIONES DE EJECUCION
 ***************************************************************************/

void setup() 
{
  Serial.begin(115200);
  
  Serial.print("Conectando a la red ");
  Serial.println(ssid);
  if(conectarAWIFI())
  {
      Serial.println("OK!");
    
      ////////////////////////////////////////////////////////////////
    
      Serial.print("Conectado a red: ");
      Serial.println(ssid);
      Serial.print("Direccion IP: ");
      Serial.println(WiFi.localIP());
      Serial.println("------------------");
      delay(300);

      ////////////////////////////////////////////////////////////////

      Serial.print("Inicializando Sensores: ");
      if(iniciarSensores()){
        Serial.println("OK!");
      } else {
        Serial.println("ERROR");
      }
      delay(300);

      ////////////////////////////////////////////////////////////////

      Serial.print("Inicializando Webserver: ");
      if(iniciarWebserver()){
        Serial.println("OK!");

        // Lo comento hasta que pueda bajar el xampp
        Serial.print("Inicializando Cliente Apache: ");
        //if(iniciarCliente()){
          Serial.println("OK!");
          Serial.println("Atendiendo peticiones y censado sensores...");
        //} else {
        //  Serial.println("ERROR");
       // }
    
    } else {
        Serial.println("ERROR");
         delay(60000000);
      }
     
    }
    else 
    {
      Serial.println("ERROR");
      delay(60000000);
    }
  
}

void loop() {
  
  server.handleClient();    // Atencion de peticiones
  
  medirSensores();          // Testear y completar
  
  evaluarMediciones();      // Al evaluar se activaran los flags de alarmas y trabas
  
  delay(500);
}


/*
 * Función conectarAWIFI()
 * Descripción: Intenta realizar una conexión Wifi segun las credenciales establecidas.
 * Devuelve true si la conexión es exitosa, sino retorna false.
 * El tiempo de intento de conexión es de X segundos.
 * Última modificación: 28/4/2018 12:12 (@mauroat)
*/
bool conectarAWIFI()
{
 /* Inicio el servidor WiFI*/
  int cantidadIntentosConexion = 0;
    
  do{
          
     WiFi.begin(ssid, password);
    // WiFi.config(ip, gateway, subred);      // Si le llegamos a fijar la IP
    WiFi.mode(WIFI_STA); 


    Serial.print("\n\tIntento ");
    Serial.print(cantidadIntentosConexion+1);
    Serial.print(": ");
    
    while ((WiFi.status() != WL_CONNECTED) && (timeoutConexion > 0)) 
    {
      delay(500);
      Serial.print(".");
      timeoutConexion--;
    }
      
    if(WiFi.status() == WL_CONNECTED)
    {
      return true;
    } 
    else 
    {
      Serial.print("\nNo pudo conectarse a la red: ");
      Serial.println(ssid);
      cantidadIntentosConexion++;
    }

    timeoutConexion = TIMEOUT_CONEXION;
  } 
  while(cantidadIntentosConexion < CANTIDAD_INTENTOS_CONEXION);
    
  return false;
    
}

/*
 * Función iniciarWebserver()
 * Descripción: Prepara las instrucciones esperadas y switchea las posibles variantes con otras funciones
 * Última modificación: 30/4/2018 14:20 (@mauroat)
*/
bool iniciarWebserver()
{
  MDNS.begin("esp8266");
  /*if (MDNS.begin("esp8266")) {
    Serial.println("MDNS responder iniciado");
  }*/
   
  /* Realiza una medicion de sensor y devuelve los datos por pantalla */
  server.on("/test", funcionTest);
    
  /* Trabar puerta */
  server.on("/lock", trabarPuertaWS);
  
  /* Desrabar puerta */
  server.on("/unlock", destrabarPuertaWS);

  /* Activar buzzer */
  server.on("/buzzon", activarBuzzerWS);

  /* Desactivar buzzer */
  server.on("/buzzoff", desactivarBuzzerWS);
  
  /* Activar ventilador */
  server.on("/fanon", activarVentiladorWS);

  /* Desactivar ventilador */
  server.on("/fanoff", desactivarVentiladorWS);

  /* Parpadear Led Amarillo */
  server.on("/amarillo", pinAmarilloONWS);

  /* Parpadear Led Azul */
  server.on("/azul", pinAzulONWS);
  
  /* Informacion de los sensores */
//  server.on("/info", informarSensores);
  
  /* Excepcion ante una peticion no reconocida*/
  server.onNotFound(handleNotFound);

    
  /* Inicio el Webserver*/
    
  server.begin();
  // Enciendo un led de encendido
  delay(300);

  return true;
}


/*
 * Función iniciarCliente()
 * Descripción: Inicializa el servicio de enviar mensajes al servidor Apache para actualizar la base de datos
 * Última modificación: 30/4/2018 13:11 (@mauroat)
*/

bool iniciarCliente()
{
  if (!client.connect(ipServidorApache, puertoIpServidorApache)) 
  {
    //Serial.println("La conexion fallo");
    //Serial.println("espere 5 segundos...");
    //delay(5000);
    return false;
  }
  return true;   
  
}



 /***************************************************************************
 *            FUNCIONES DE ACCION ANTE UNA PETICION DEL WEBSERVER
 ***************************************************************************/

void pinAmarilloONWS ()
{
  Serial.println("Instruccion recibida: Pin Amarillo");
  parpadearLed(pinLEDAmarillo);
}

void pinAzulONWS ()
{
  Serial.println("Instruccion recibida: Pin Azul");
  parpadearLed(pinLEDAzul);
}

void trabarPuertaWS()
{
    Serial.println("Instruccion recibida: Trabar puerta");
    trabarPuerta();
}
void destrabarPuertaWS()
{
     Serial.println("Instruccion recibida: Destrabar puerta");
    destrabarPuerta();
}
void activarBuzzerWS()
{
  Serial.println("Instruccion recibida: Encender buzzer");
  activarBuzzer();
}
void desactivarBuzzerWS()
{  
  Serial.println("Instruccion recibida: Apagar buzzer");
  desactivarBuzzer();
}
void activarVentiladorWS()
{
  Serial.println("Instruccion recibida: Encender ventilador");
  activarVentilador();
}
void desactivarVentiladorWS()
{
  Serial.println("Instruccion recibida: Apagar ventilador");
  desactivarVentilador();
}

/*
 * Función enviarAlServidorWS(int,int)
 * Descripción: Envia un mensaje GET al servidor Apache informando un cambio de estado en los actuadores
 * Última modificación: 28/4/2018 19:58 (@mauroat)
*/
void enviarAlServidorWS(int accion, int disparador)
{
    String contenido = "GET /selfieHouse/ws/controllerArduino.php?accion=";
        contenido += accion;
        contenido += "&disparador=";
        contenido += disparador;
    client.print(contenido);
}


bool iniciarSensores()
{
  /* Inicializo Buzzer */
  pinMode(pinBuzzer, OUTPUT);
  analogWrite(pinBuzzer,150);   
  estadoBuzzer = DESACTIVADO;
  
  /* Inicializo Sensor de Temperatura */
  estadoTyH = DESACTIVADO;
  
  /* Inicializo Sensor de Movimiento */
  estadoMovimiento = DESACTIVADO;
  medicionMovimiento = DESACTIVADO;

  /* Inicializo ventilador */
  estadoVentilador = DESACTIVADO;
  pinMode(pinVentilador, OUTPUT);

   /* Inicializo servo */
   estadoTraba = ACTIVADO;
   servoTrabaPuerta.attach(pinServo);  
   //trabarPuerta();
    
   /* Inicializo LEDs  */
   pinMode(pinLEDAzul, OUTPUT);
   pinMode(pinLEDAmarillo, OUTPUT);
   //pinMode(pinLEDRojo, OUTPUT);
   //pinMode(pinLEDVerde, OUTPUT);

   return true;
}

 /***************************************************************************
 *            FUNCIONES DE MEDICION Y CONTROL DE SENSORES
 ***************************************************************************/


/*
 * Función medirSensores()
 * Descripción: Censa cada uno de los sensores y carga los valores en las variables de medicion y estado.
 * Última modificación: 28/4/2018 19:58 (@mauroat)
*/
void medirSensores()
{
  /* Por orden de importancia */
  
  medicionLlama = medirLlama();
  estadoMovimiento = medirMovimiento(); //FALTA TESTEAR
  medicionTemperatura = medirTemperatura();
  medicionHumedad = medirHumedad();
  //medicionLuz = medirLuz();         FALTA
  
  Serial.print(mostrarMediciones());

}

bool evaluarMediciones ()
{
  /* Evaluo por orden de importancia */
  
  Serial.print("Se evalúa el nivel de llama: ");
  if(medicionLlama > TOPE_LLAMA)
  {
    // No hay fuego
	Serial.println("Normal");

  } else {
    
    
    // Activo el Buzzer e informo al servidor Apache
      Serial.println("FUEGO!");
      estadoBuzzer = ACTIVADO;
      activarBuzzer();
      /*
      if(estadoBuzzer == DESACTIVADO)
      {
        Serial.println("Se activa la alarma");
        estadoBuzzer = ACTIVADO;
        activarBuzzer();
      } else {
         Serial.println("Alarma ya activada, no la modifico");
      }
      */
      
      if(estadoLlama == DESACTIVADO)
      {
        estadoLlama = ACTIVADO;
        enviarAlServidorWS(BUZZER_ACTIVADO,DISPARADOR_LLAMA);
      } else {
        // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
      }
	  
  }

  Serial.print("Se evalúa la detección de movimiento: ");
  /*
  * medicionMovimiento = Medicion instantanea del movimiento del sensor
  * estadoMovimiento = FLAG que determina si el controlador ya esta alertado que hubo movimiento. Se usa para no lanzar la alarma dos veces en caso que el movimiento no cese.
  */
  if(medicionMovimiento == DESACTIVADO && estadoMovimiento == DESACTIVADO)
  {
      Serial.println("No se detectó movimiento");  
  } else {
    
    Serial.println("Se detectó movimiento");
    estadoBuzzer = ACTIVADO;
    activarBuzzer();
    /*
    if(estadoBuzzer == DESACTIVADO)
      {
        Serial.println("Se activa la alarma");
        estadoBuzzer = ACTIVADO;
        activarBuzzer();
      } else {
         Serial.println("Alarma ya activada, no la modifico");
      }
      */
      
      if(estadoMovimiento == DESACTIVADO)
      {
        Serial.println("Aviso al servidor Apache");
        estadoMovimiento = ACTIVADO;
        enviarAlServidorWS(BUZZER_ACTIVADO,DISPARADOR_MOVIMIENTO);
      } else {
        // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
      }
   
  }  
  
  Serial.print("Se evalua la temperatura y humedad: ");
  if(medicionTemperatura <= TOPE_TEMPERATURA && medicionHumedad <= TOPE_HUMEDAD)
  {
      Serial.println("Temperatura en rango aceptable");  
  } else {
    
     Serial.println("Temperatura excedida. Se enciende ventilador");
     estadoVentilador = ACTIVADO;
     activarVentilador();
     /*
     if(estadoVentilador == DESACTIVADO)
     {
        Serial.println("Activo el ventilador");
        estadoVentilador = ACTIVADO;
        activarVentilador();
     } else {
        Serial.println("Ventilador encendido, no lo modifico");
     }
    */
      
      if(estadoTyH == DESACTIVADO)
      {
        Serial.println("Aviso al servidor Apache");
        estadoTyH = ACTIVADO;
        enviarAlServidorWS(VENTILADOR_ACTIVADO,DISPARADOR_TEMPERATURA);
      } else {
        // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
      }
 
  }  
  
}


/*
 * Función mostrarMediciones()
 * Descripción: Devuelve un string con los valores de medicion y estado.
 * Última modificación: 28/4/2018 19:58 (@mauroat)
*/
String mostrarMediciones()
{
  String contenido = "";

    contenido += "\nLlama: ";
    contenido += medicionLlama; 
    contenido += "\nMovimiento:" ;
    contenido += estadoMovimiento;
    contenido += "\nTemperatura:" ;
    contenido += medicionTemperatura;
    contenido += "\nHumedad:" ;
    contenido += medicionHumedad;
    contenido += "\n:" ;
    return contenido;
}

float medirTemperatura()
{
  return sensorTempyHum.readTemperature();
}

float medirHumedad()
{
  return sensorTempyHum.readHumidity();
}

float medirLlama()
{
  return analogRead(pinSensorLlama)* (5.0 / 1023.0); ;
}

void activarVentilador()
{
  estadoVentilador = ACTIVADO;
  digitalWrite(pinVentilador, HIGH);
}

void desactivarVentilador()
{
  estadoVentilador = DESACTIVADO;
  digitalWrite(pinVentilador, LOW);
}

void trabarPuerta()
{

  if(estadoTraba == DESACTIVADO)
  {
    for(int pos = 0; pos <= 90; pos += 1) // goes from 0 degrees to 180 degrees 
    {                                  // in steps of 1 degree 
      servoTrabaPuerta.write(pos);     // tell servo to go to position in variable 'pos' 
      delay(15);                       // waits 15ms for the servo to reach the position 
    }
    estadoTraba = ACTIVADO;
  } else {
    Serial.println("La puerta ya esta trabada");
   }
}

void destrabarPuerta()
{
  
  if(estadoTraba == ACTIVADO)
  {
    for(int pos = 90; pos>=0; pos-=1)     // goes from 180 degrees to 0 degrees 
    {                                
      servoTrabaPuerta.write(pos);      // tell servo to go to position in variable 'pos' 
      delay(15);                       // waits 15ms for the servo to reach the position 
    } 
    estadoTraba = DESACTIVADO;
  } else {
    Serial.println("La puerta ya esta destrabada");
   }
}

void parpadearLed(int pin)
{
  digitalWrite(pin, LOW);
  delay(TIEMPO_PARPADEO);
  digitalWrite(pin, HIGH);
}

  
/*
 * Función medirMovimiento()
 * Descripción: Detecta HIGH o LOW en el pin indicado y devuelve ACTIVADO en caso de detectar movimiento. Caso contrario devuelve DESACTIVADO
 * Última modificación: 28/4/2018 20:13 (@mauroat)
 * Testear!!!!!!
 * 
*/
int medirMovimiento()
{
  if(digitalRead(pinSensorMovimiento) == HIGH)
  {
    //digitalWrite(led,LOW);      
    return ACTIVADO;
 //   Serial.println("Detectado movimiento por el sensor pir");
 //   digitalWrite(led,HIGH);
 //   delay(1000);
 //   digitalWrite(led,LOW);
  }
  else
  {
    return DESACTIVADO;  
  }
}

void activarBuzzer()
{
  estadoBuzzer = ACTIVADO;
  tone(pinBuzzer,500,1000);
  delay(1000);
  tone(pinBuzzer,1000,1000);
  delay(1000); 
}

void desactivarBuzzer()
{
  estadoBuzzer = DESACTIVADO;
}
 /***************************************************************************
 *            FUNCIONES DE RESPUESTA DE WEBSERVER
 ***************************************************************************/

void funcionTest()
{
  String contenido = "";
      contenido += "<!DOCTYPE HTML>";
      contenido += "<html>";
      contenido += "<head>";
      contenido += "<meta name = \"viewport\" content = \"width = device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable=0\">";
      contenido += "<meta charset = \"utf-8\" >";
      contenido += "<title>selfieHouse</title>";
      contenido += "<style>";
      contenido += "\"body { background-color: #808080; font-family: Arial, Helvetica, Sans-Serif; Color: #000000; }\"";
      contenido += "</style>";
      contenido += "</head>";
      contenido += "<body bgcolor=\"#c5d8b2\">";
     
      contenido += "<h2>Esta es una prueba de SOA</h2>";
      contenido += "</body>";
    
    server.send(200, "text/html", contenido);       
    
}

void handleNotFound()
{
  String message = "<!DOCTYPE html><html><head><h1><b>404</b> - No encontrado</h1></head>\n\n";
  message += "URI: ";
  message += server.uri();
  message += "\nMetodos: ";
  message += (server.method() == HTTP_GET)?"GET":"POST";
  message += "\nArgumentos: ";
  message += server.args();
  message += "</html>\n";
  for (uint8_t i=0; i<server.args(); i++)
  {
    message += " " + server.argName(i) + ": " + server.arg(i) + "\n";
  }
  server.send(404, "text/plain", message);
  //parpadearLed(pinAuxiliar);
}


