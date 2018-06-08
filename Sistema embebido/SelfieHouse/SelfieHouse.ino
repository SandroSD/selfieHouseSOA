/****************************************************************************
  |--------------------------------------------------------------------------
  | Proyecto      : selfieHouse
  | Version       : 1.0.1
  | Actualizado   : 26/05/2018
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


/* Bibliotecas */
#include <DHT.h>
#include <ArduinoJson.h>
#include <ESP8266WebServer.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266mDNS.h>
#include <Servo.h>

/* Constantes */

#define DHTTYPE DHT22

#define MODO_PRODUCTIVO      1
#define MODO_DEBUG        	 2

#define CANTIDAD_INTENTOS_CONEXION 3
#define TIMEOUT_CONEXION    10
#define TIEMPO_PARPADEO     400       // ms
#define ACTIVADO        1
#define DESACTIVADO       0

#define TOPE_FLAMA        1
#define TOPE_TEMPERATURA    30
//#define TOPE_HUMEDAD      85
#define TOPE_LUZ        10

#define PUERTA_TRABADA        1000
#define PUERTA_DESTRABADA     1001
#define BUZZER_ACTIVADO       1002
#define BUZZER_DESACTIVADO    1003
#define VENTILADOR_ACTIVADO     1004
#define VENTILADOR_DESACTIVADO  1005

#define DISPARADOR_MOVIMIENTO   2000
#define DISPARADOR_FLAMA        2001
#define DISPARADOR_TEMPERATURA  2002
#define DISPARADOR_LUZ          2003
#define DISPARADOR_MANUAL       2004

#define ID_TRABA			1
#define ID_VENTILADOR		2
#define ID_BUZZER			3
#define ID_LED_VERDE		4
#define ID_LED_ROJO			5


/* Modo de ejecucion */
int estadoSelfieHouse;
int modoEjecucion;

/* Pines digitales */
int pinSensorTempyHum = 4;               // GPIO04 - D2
int pinSensorMovimiento = 14;       // GPIO14 - D5
int pinVentilador = 15;             // GPIO15 - D8
int pinServo = 10;                // GPIO10 - SD03
int pinBuzzer = 3;                  // GPIO03 - RX
int pinLEDVerde = 5;                // GPIO05 - D1
int pinLEDRojo = 16;                  // GPIO16 - D0
int pinSensorFlama = 12;            // GPIO12 - D6

/* Pines analogicos */
int pinSensorLuz = A0;        // A0


/* Sensores tipo objeto */
DHT sensorTempyHum(pinSensorTempyHum, DHTTYPE);
Servo servoTrabaPuerta;

/* Mediciones */
float medicionTemperatura, medicionHumedad, medicionSensacionTermica, medicionFlama;
double medicionLuz;
int medicionMovimiento;

/* Flags y Semaforos*/
int estadoBuzzer, estadoTraba, estadoVentilador, estadoWebserver, estadoFlama, estadoTyH, estadoMovimiento;


/* Conexion de red */
ESP8266WebServer server(80);    // Webserver
ESP8266WiFiMulti WiFiMulti;     // Responder de peticiones
WiFiClient client;              // Cliente que avisa al servidor Apache


int timeoutConexion = 10 ;      // 5 segundos para conectarse al Wifi
const char* ssid = "red";
const char* password = "*****";
const char * ipServidorApache = "192.168.1.10";              // Servidor Apache - Hay que disponer de una IP fija
const uint16_t puertoIpServidorApache = 8080;                  // Puerto Servidor Apache

/***************************************************************************
             FUNCIONES DE EJECUCION
***************************************************************************/

void setup()
{
  modoEjecucion = MODO_DEBUG;
  estadoSelfieHouse = DESACTIVADO;
  
  /* Inicializo LEDs  */
  pinMode(pinLEDVerde, OUTPUT);
  pinMode(pinLEDRojo, OUTPUT);

  Serial.begin(115200);

  Serial.println("**** selfieHouse ****");
  delay(1000);
  Serial.println("¡Bienvenido!");
  delay(2000);
  
  /* En modo PRODUCCION inicializo todos los servicios */
  if(modoEjecucion != 4)
  {
   Serial.print("Conectando a la red ");
   Serial.println(ssid);
   if (conectarAWIFI())
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
    if (iniciarSensores()) {
      parpadearLed(pinLEDVerde);
      Serial.println("OK!");
    } else {
      digitalWrite(pinLEDRojo, HIGH);
      Serial.println("ERROR");
      delay(60000000);
    }
    delay(300);

    ////////////////////////////////////////////////////////////////

    Serial.print("Inicializando Webserver: ");
    if (iniciarWebserver()) {
      parpadearLed(pinLEDVerde);
      Serial.println("OK!");

      // Lo comento hasta que pueda bajar el xampp
      Serial.print("Inicializando Cliente Apache: ");
      if (iniciarCliente()) {
      parpadearLed(pinLEDVerde);
      Serial.println("OK!");
      Serial.println("Atendiendo peticiones y censado sensores...");
      digitalWrite(pinLEDVerde, HIGH);
      } else {
      digitalWrite(pinLEDRojo, HIGH);
      Serial.println("ERROR");
      delay(60000000);

      }

    } else {
      digitalWrite(pinLEDRojo, HIGH);
      Serial.println("ERROR");
      delay(60000000);
    }

    }
    else
    {
    digitalWrite(pinLEDRojo, HIGH);
    Serial.println("ERROR");
    delay(60000000);
    } 
  } 
  
  else 
  /* En modo DEBUG solo inicializo los sensores */
  {  
    if (iniciarSensores()) {
      parpadearLed(pinLEDVerde);
      Serial.println("OK!");
    } else {
      digitalWrite(pinLEDRojo, HIGH);
      Serial.println("ERROR");
      delay(60000000);
    }
    ////////////////////////////////////////////////////////////////    
  }
  
  

}

void loop() {

  /* Atencion de peticiones */
  server.handleClient();    
  
  /* Obtengo datos de sensores */
  medirSensores();          

  /* Si el estado de la casa está activado, evaluare las mediciones tomadas */
  estadoSelfieHouse == ACTIVADO ? evaluarMediciones() : false;

     
}


/*
   Función conectarAWIFI()
   Descripción: Intenta realizar una conexión Wifi segun las credenciales establecidas.
   Devuelve true si la conexión es exitosa, sino retorna false.
   El tiempo de intento de conexión es de X segundos.
   Última modificación: 28/4/2018 12:12 (@mauroat)
*/
bool conectarAWIFI()
{
  /* Inicio el servidor WiFI*/
  int cantidadIntentosConexion = 0;

  do {

    WiFi.begin(ssid, password);
    // WiFi.config(ip, gateway, subred);      // Si le llegamos a fijar la IP
    WiFi.mode(WIFI_STA);

    Serial.print("\n\tIntento ");
    Serial.print(cantidadIntentosConexion + 1);
    Serial.print(": ");

    while ((WiFi.status() != WL_CONNECTED) && (timeoutConexion > 0))
    {
      delay(500);
      Serial.print(".");
      timeoutConexion--;
    }

    if (WiFi.status() == WL_CONNECTED)
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
  while (cantidadIntentosConexion < CANTIDAD_INTENTOS_CONEXION);

  return false;

}

/*
   Función iniciarWebserver()
   Descripción: Prepara las instrucciones esperadas y switchea las posibles variantes con otras funciones
   Última modificación: 30/4/2018 14:20 (@mauroat)
*/
bool iniciarWebserver()
{
  if(MDNS.begin("esp8266"))
  {
    /* Activar selfieHouse */
    server.on("/selfieon", activarSelfieHouseWS); 
    
    /* Desactivar selfieHouse */
    server.on("/selfieoff", desactivarSelfieHouseWS);
    
    /* Activar Modo DEBUG */
    server.on("/debugon", activarDebugWS);
    
    /* Desactivar Modo DEBUG */
    server.on("/debugoff", desactivarDebugWS);
    
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

    /* Encender Led Rojo */
    server.on("/redon", pinRojoONWS);

    /* Apagar Led Rojo */
    server.on("/redoff", pinRojoOFFWS);

    /* Encender Led Verde */
    server.on("/greenon", pinVerdeONWS);

    /* Apagar Led Verde */
    server.on("/greenoff", pinVerdeOFFWS);

    /* Informacion de los sensores */
    server.on("/infoSensores", infoSensores);

	/* Informacion de los estados de actuadores */
    server.on("/infoActuadores", infoActuadores);
	
    /* Excepcion ante una peticion no reconocida*/
    server.onNotFound(notFound);

    /* Inicio el Webserver*/
    server.begin();
    
    delay(300);

    return true;
  } else {
    Serial.println("ERROR");
    delay(60000000);
    return false;
    
  }
  
  
  
}


/*
   Función iniciarCliente()
   Descripción: Inicializa el servicio de enviar mensajes al servidor Apache para actualizar la base de datos
   Última modificación: 23/05/2018 17:11 (@mauroat)
*/

bool iniciarCliente()
{
  return client.connect(ipServidorApache, puertoIpServidorApache) ? true : false;
}



/***************************************************************************
             FUNCIONES DE ACCION ANTE UNA PETICION DEL WEBSERVER
***************************************************************************/
void activarSelfieHouseWS(){
  Serial.println("Instruccion recibida: Estado selfieHouse ACTIVADO");
  estadoSelfieHouse = ACTIVADO;
  enviarRespuesta("OK");
}

void desactivarSelfieHouseWS(){
  Serial.println("Instruccion recibida: Estado selfieHouse DESACTIVADO");
  estadoSelfieHouse = DESACTIVADO;
  enviarRespuesta("OK");
}

void activarDebugWS ()
{
  Serial.println("Instruccion recibida: Modo DEBUG ON");
  modoEjecucion = MODO_DEBUG;
  enviarRespuesta("OK");
}

void desactivarDebugWS ()
{
  Serial.println("Instruccion recibida: Modo DEBUG OFF - Cambio a modo PRODUCCION");
  modoEjecucion = MODO_PRODUCTIVO;
  enviarRespuesta("OK");
}

void pinRojoONWS ()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Pin Rojo ON") : false;
  digitalWrite(pinLEDRojo, HIGH);
  enviarRespuesta("OK");
}

void pinRojoOFFWS ()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Pin Rojo OFF") : false;
  digitalWrite(pinLEDRojo, LOW);
  enviarRespuesta("OK");
}

void pinVerdeONWS ()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Pin Verde ON") : false;
  digitalWrite(pinLEDVerde, HIGH);
  enviarRespuesta("OK");
}

void pinVerdeOFFWS ()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Pin Verde OFF") : false;
  digitalWrite(pinLEDVerde, LOW);
  enviarRespuesta("OK");
}

void trabarPuertaWS()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Trabar puerta") : false;
  trabarPuerta();
  enviarRespuesta("OK");
}
void destrabarPuertaWS()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Destrabar puerta") : false;
  parpadearLed(pinLEDVerde);
  destrabarPuerta();
  parpadearLed(pinLEDVerde);
  enviarRespuesta("OK");
}
void activarBuzzerWS()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Encender buzzer") : false;
  activarBuzzer();
  enviarRespuesta("OK");
}
void desactivarBuzzerWS()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Apagar buzzer") : false;
  desactivarBuzzer();
  enviarRespuesta("OK");
}
void activarVentiladorWS()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Encender ventilador") : false;
  activarVentilador();
  enviarRespuesta("OK");
}
void desactivarVentiladorWS()
{
  modoEjecucion == MODO_DEBUG ? Serial.println("Instruccion recibida: Apagar ventilador") : false;
  desactivarVentilador();
  enviarRespuesta("OK");
}

/*
   Función enviarAlServidorWS(int,int)
   Descripción: Envia un mensaje GET al servidor Apache informando un cambio de estado en los actuadores
   Última modificación: 23/5/2018 17:58 (@mauroat)
   Comentario: Esta URL habra que modificarla 5/5/2018 19:58 (@mauroat)
         URL corregida 23/5/2018 17:58 (@mauroat)
*/
void enviarAlServidorWS(int accion, int disparador)
{
  String contenido = "GET /selfieHouse/ws/ArduinoReceiver.php?accion=";
  contenido += accion;
  contenido += "&disparador=";
  contenido += disparador;
  client.println(contenido);
}

void enviarRespuesta(String respuesta)
{
  StaticJsonBuffer<200> jsonBuffer;
  JsonObject& json = jsonBuffer.createObject();
  json["respuesta"] = respuesta; 
  
  String jsonChar;
  json.prettyPrintTo(jsonChar);
  server.send(200, "application/json", jsonChar); 
}

bool iniciarSensores()
{
  /* Inicializo Buzzer */
  pinMode(pinBuzzer, OUTPUT);
  //analogWrite(pinBuzzer, 150);
  estadoBuzzer = DESACTIVADO;

  /* Inicializo Sensor de Temperatura */
  estadoTyH = DESACTIVADO;

  /* Inicializo Sensor de Movimiento */
  pinMode(pinSensorMovimiento, INPUT);
  estadoMovimiento = DESACTIVADO;
  medicionMovimiento = DESACTIVADO;

  /* Inicializo ventilador */
  estadoVentilador = DESACTIVADO;
  pinMode(pinVentilador, OUTPUT);
  digitalWrite(pinVentilador, LOW);

  /* Inicializo servo */
  estadoTraba = ACTIVADO;
  servoTrabaPuerta.attach(pinServo);
  trabarPuerta();    // Aca ver de comenzar con la traba puesta


  return true;
}

/***************************************************************************
             FUNCIONES DE MEDICION Y CONTROL DE SENSORES
***************************************************************************/


/*
   Función medirSensores()
   Descripción: Censa cada uno de los sensores y carga los valores en las variables de medicion y estado.
   Última modificación: 28/4/2018 19:58 (@mauroat)
*/
void medirSensores()
{
  /* Por orden de importancia */

  medicionFlama = medirFlama();
  estadoMovimiento = medirMovimiento();
  medicionTemperatura = medirTemperatura();
  //medicionHumedad = medirHumedad();
  medicionLuz = medirLuz();
  
  //Serial.print(mostrarMediciones());

}

bool evaluarMediciones ()
{
  /* Evaluo por orden de importancia */

  Serial.print("\nNivel de luz: ");
  if( medicionLuz > 10 && medicionLuz < 1000){
    Serial.println("Bajo");
    
  } else if (medicionLuz >= 1000 && medicionLuz<2000){
	  Serial.println("Medio");
  }
  
  else {
    Serial.println("Alto");
    Serial.println("Se analiza el nivel de flama...");
    Serial.print("Nivel de flama: ");
    
    if (medicionFlama == LOW)
    {
    // No hay fuego
    Serial.println("Normal");

    } else {

    // Activo el Buzzer e informo al servidor Apache
    Serial.println("FUEGO!");
    estadoBuzzer = ACTIVADO;
    activarBuzzer();
    digitalWrite(pinLEDRojo, HIGH);
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

    if (estadoFlama == DESACTIVADO)
    {
      estadoFlama = ACTIVADO;
      enviarAlServidorWS(BUZZER_ACTIVADO, DISPARADOR_FLAMA);
    } else {
      // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
    }

    }
  }
  
  Serial.print("Detección de movimiento: ");
  /*
    medicionMovimiento = Medicion instantanea del movimiento del sensor
    estadoMovimiento = FLAG que determina si el controlador ya esta alertado que hubo movimiento. Se usa para no lanzar la alarma dos veces en caso que el movimiento no cese.
  */
  if (medicionMovimiento == DESACTIVADO && estadoMovimiento == DESACTIVADO)
  {
    Serial.println("No se detectó movimiento");
    digitalWrite(pinLEDRojo, LOW);
  } else {

    Serial.println("Se detectó movimiento");
    estadoBuzzer = ACTIVADO;
    activarBuzzer();
    digitalWrite(pinLEDRojo, HIGH);
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

    if (estadoMovimiento == DESACTIVADO)
    {
      Serial.println("Aviso al servidor Apache");
      estadoMovimiento = ACTIVADO;
      enviarAlServidorWS(BUZZER_ACTIVADO, DISPARADOR_MOVIMIENTO);
    } else {
      // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
    }

  }

  Serial.print("Se evalua la temperatura y humedad: ");
  if (medicionTemperatura <= TOPE_TEMPERATURA)
  { 
  Serial.println("Temperatura en rango aceptable");
  } else {
    if (estadoVentilador == DESACTIVADO) {
      Serial.println("Temperatura excedida. Se enciende ventilador");
      estadoVentilador = ACTIVADO;
      activarVentilador();
      // Avisar al Apache

    } else {
      Serial.println("Temperatura excedida. El ventilador ya esta encendido");

    }


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

    if (estadoTyH == DESACTIVADO)
    {
      Serial.println("Aviso al servidor Apache");
      estadoTyH = ACTIVADO;
      enviarAlServidorWS(VENTILADOR_ACTIVADO, DISPARADOR_TEMPERATURA);
    } else {
      // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
    }

  }

}


/*
   Función mostrarMediciones()
   Descripción: Devuelve un string con los valores de medicion y estado.
   Última modificación: 28/4/2018 19:58 (@mauroat)
*/
String mostrarMediciones()
{
  String contenido = "";
  contenido += "\n*MEDICIONES*";
  contenido += "\nLuz: ";
  contenido += medicionLuz;
  contenido += "\nFlama: ";
  digitalRead(medicionFlama == HIGH) ? contenido += "Encendido" : contenido += "Apagado";
  contenido += "\nMovimiento:" ;
  estadoMovimiento == ACTIVADO ? contenido += "Hay movimiento" : contenido += "No hay movimiento";

  contenido += "\nTemperatura:" ;
  contenido += medicionTemperatura;
  contenido += "\n" ;
  contenido += "\n*ESTADOS FLAGS*";

  contenido += "\nEstado Buzzer:";
  contenido += estadoBuzzer;
  contenido += "\nEstado Traba:";
  contenido += estadoTraba;
  contenido += "\nEstado Flama:";
  contenido += estadoFlama;
  contenido += "\nEstado Ventilador:";
  contenido += estadoVentilador;
  contenido += "\nEstado Movimiento:";
  contenido += estadoMovimiento;
  contenido += "\nEstado TempyHum:";
  contenido += estadoTyH;
  contenido += "\nEstado Pin Verde:";
  digitalRead(pinLEDVerde == HIGH) ? contenido += "Encendido" : contenido += "Apagado";
  contenido += "\nEstado Pin Rojo:";
  digitalRead(pinLEDRojo == HIGH) ? contenido += "Encendido" : contenido += "Apagado";  

  return contenido;
}

float medirTemperatura()
{
  return sensorTempyHum.readTemperature();
}

/*float medirHumedad()
{
  return sensorTempyHum.readHumidity();
}*/

float medirFlama()
{
  return digitalRead(pinSensorFlama);
  //return analogRead(pinSensorFlama) * (5.0 / 1023.0); 
}

float medirLuz()
{
  double valor = analogRead(pinSensorLuz); 
  return ((1023-valor) * 10 /valor);
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

  if (estadoTraba == DESACTIVADO)
  {
    for (int pos = 0; pos <= 90; pos += 1) // goes from 0 degrees to 90 degrees
    { // in steps of 1 degree
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

  if (estadoTraba == ACTIVADO)
  {
    for (int pos = 90; pos >= 0; pos -= 1) // goes from 90 degrees to 0 degrees
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
   Función medirMovimiento()
   Descripción: Detecta HIGH o LOW en el pin indicado y devuelve ACTIVADO en caso de detectar movimiento. Caso contrario devuelve DESACTIVADO
   Última modificación: 28/4/2018 20:13 (@mauroat)
   Testear!!!!!!

*/
int medirMovimiento()
{
  return digitalRead(pinSensorMovimiento) == HIGH ? ACTIVADO : DESACTIVADO;
}

void activarBuzzer()
{
  estadoBuzzer = ACTIVADO;
  tone(pinBuzzer, 500, 1000);
  //delay(1000);
  tone(pinBuzzer, 1000, 1000);
  //delay(1000);
}

void desactivarBuzzer()
{
  estadoBuzzer = DESACTIVADO;
  noTone(pinBuzzer);
}
/***************************************************************************
             FUNCIONES DE RESPUESTA DE WEBSERVER
***************************************************************************/
void infoSensores()
{
  const size_t bufferSize = JSON_ARRAY_SIZE(2) + JSON_OBJECT_SIZE(5) + 60;
  DynamicJsonBuffer jsonBuffer(bufferSize);

  JsonObject& json = jsonBuffer.createObject();
  JsonObject& s1 = jsonBuffer.createObject();

  s1["nombre"] = "Temperatura";                 
  s1["valor"] = medicionTemperatura;

  JsonObject& s2 = jsonBuffer.createObject(); 

  s2["nombre"] = "Movimiento";            
  estadoMovimiento == ACTIVADO ? s2["valor"]= "Hay movimiento" : s2["valor"] = "No hay movimiento";
  
  JsonObject& s3 = jsonBuffer.createObject();
  
  s3["nombre"] = "Luz";
  if( medicionLuz > 10 && medicionLuz < 1000){
    s3["valor"] = "Bajo";
  } else if (medicionLuz >= 1000 && medicionLuz < 2000){
    s3["valor"] = "Medio";
  } else {
    s3["valor"] = "Alto";
  }
 
  JsonObject& s4 = jsonBuffer.createObject();
  s4["nombre"] = "Flama";
  medicionFlama == HIGH ? s4["valor"] =  "Hay fuego" : s4["valor"] = "No hay fuego";
  

  JsonObject& s5 = jsonBuffer.createObject();
  s5["nombre"] = "Ventilador";
  digitalRead(pinLEDVerde) == HIGH ? s5["valor"] = "Encendido" : s5["valor"] = "Apagado"; 

  JsonArray& datosS = json.createNestedArray("sensores");
  datosS.add(s1);
  datosS.add(s2);
  datosS.add(s3);
  datosS.add(s4);

  json.prettyPrintTo(Serial);
  String jsonChar;
  json.prettyPrintTo(jsonChar);
  server.send(200, "application/json", jsonChar); 
 
}


void infoActuadores()
{
  const size_t bufferSize = JSON_ARRAY_SIZE(2) + JSON_OBJECT_SIZE(5) + 60;
  DynamicJsonBuffer jsonBuffer(bufferSize);

  JsonObject& json = jsonBuffer.createObject();
  JsonObject& s1 = jsonBuffer.createObject();

  s1["id"] = ID_TRABA;
  s1["nombre"] = "Traba";                 
  estadoTraba == ACTIVADO ? s1["valor"] = "Encendida" : s1["valor"] = "Apagada";

  JsonObject& s2 = jsonBuffer.createObject(); 

  s2["id"] = ID_BUZZER;
  s2["nombre"] = "Buzzer";            
  estadoBuzzer == ACTIVADO ? s2["valor"] = "Encendido" : s2["valor"] = "Apagado";

  JsonObject& s3 = jsonBuffer.createObject();
  s3["id"] = ID_VENTILADOR;
  s3["nombre"] = "Ventilador";
  estadoVentilador == ACTIVADO ? s3["valor"] = "Encendido" : s3["valor"] = "Apagado";

  JsonObject& s4 = jsonBuffer.createObject();
  s4["id"] = ID_LED_ROJO;
  s4["nombre"] = "Ventilador";
  digitalRead(pinLEDRojo) == HIGH ? s4["valor"] = "Encendido" : s4["valor"] = "Apagado"; 

  JsonObject& s5 = jsonBuffer.createObject();
  s5["id"] = ID_LED_VERDE;
  s5["nombre"] = "Ventilador";
  digitalRead(pinLEDVerde) == HIGH ? s5["valor"] = "Encendido" : s5["valor"] = "Apagado"; 

  JsonArray& datosS = json.createNestedArray("actuadores");
  datosS.add(s1);
  datosS.add(s2);
  datosS.add(s3);
  datosS.add(s4);
  datosS.add(s5);

  json.prettyPrintTo(Serial);
  String jsonChar;
  json.prettyPrintTo(jsonChar);
  server.send(200, "application/json", jsonChar);
}

void notFound()
{
  String message = "<!DOCTYPE html><html><head><h1><b>404</b> - El comando elegido no se reconoce</h1></head>\n\n";
  message += "URI: ";
  message += server.uri();
  message += "\nMetodos: ";
  message += (server.method() == HTTP_GET) ? "GET" : "POST";
  message += "\nArgumentos: ";
  message += server.args();
  message += "</html>\n";
  for (uint8_t i = 0; i < server.args(); i++)
  {
    message += " " + server.argName(i) + ": " + server.arg(i) + "\n";
  }
  server.send(404, "text/html", message);
  //parpadearLed(pinAuxiliar);
}



