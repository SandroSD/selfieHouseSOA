/****************************************************************************
  |--------------------------------------------------------------------------
  | Proyecto      : selfieHouse
  | Version       : 1.0.1
  | Actualizado   : 22/06/2018
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
#define MODO_DEBUG           2

#define CANTIDAD_INTENTOS_CONEXION 3
#define TIMEOUT_CONEXION    10
#define TIEMPO_PARPADEO     300       // ms
#define ACTIVADO        1
#define DESACTIVADO       0

#define TOPE_FLAMA        1
#define TOPE_TEMPERATURA    23
#define TOPE_LUZ        10

#define PUERTA_TRABADA        1000
#define PUERTA_DESTRABADA     1001
#define BUZZER_ACTIVADO       1002
#define BUZZER_DESACTIVADO    1003
#define VENTILADOR_ACTIVADO     1004
#define VENTILADOR_DESACTIVADO  1005
#define SELFIEHOUSE_ACTIVADO  1006
#define SELFIEHOUSE_DESACTIVADO  1007
#define REINCIO_ESTADOS  9999

#define DISPARADOR_MOVIMIENTO   2000
#define DISPARADOR_FLAMA        2001
#define DISPARADOR_TEMPERATURA  2002
#define DISPARADOR_LUZ          2003
#define DISPARADOR_MANUAL       2004
#define DISPARADOR_AUTOMATICO       2005

#define ID_TRABA      1
#define ID_BUZZER      2
#define ID_VENTILADOR   3
#define ID_LED_ROJO      4
#define ID_LED_VERDE    5

#define TIEMPO_CENSADO 150



/* Modo de ejecucion */
int estadoSelfieHouse;
int modoEjecucion;

/* Pines digitales */
int pinSensorTempyHum = 4;          // GPIO04 - D2
int pinSensorMovimiento = 14;       // GPIO14 - D5
int pinVentilador = 15;             // GPIO15 - D8
int pinServo = 10;                  // GPIO10 - SD03
int pinBuzzer = 3;                  // GPIO03 - RX
int pinLEDVerde = 5;                // GPIO05 - D1
int pinLEDRojo = 16;                // GPIO16 - D0
int pinLEDAzul = 2;                 // GPIO02 - D4
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
unsigned long startMillis;  //some global variables available anywhere in the program
unsigned long currentMillis;

/* Conexion de red */
ESP8266WebServer server(80);    // Webserver
ESP8266WiFiMulti WiFiMulti;     // Responder de peticiones
WiFiClient client;              // Cliente que avisa al servidor Apache


int timeoutConexion = 10 ;      // 5 segundos para conectarse al Wifi
const char* ssid = "WF_selfieHouse";    
const char* password = "selfiehouse"; 
const char * ipServidorApache = "192.168.1.10";              // Servidor Apache
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
  pinMode(pinLEDAzul, OUTPUT);
  
  Serial.begin(115200);

  Serial.println("**** selfieHouse ****");
  delay(1000);
  Serial.println("¡Bienvenido!");
  delay(2000);
  
  /* En modo PRODUCCION inicializo todos los servicios */
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
      if (iniciarSensores()) {
        parpadearLed(pinLEDVerde);
        Serial.println("OK!");
        delay(300);
      } else {
        digitalWrite(pinLEDVerde, LOW);
        digitalWrite(pinLEDRojo, HIGH);
        Serial.println("ERROR");
        delay(60000000);
      }
      

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
          Serial.println("Reiniciando estados en la base de datos");
          enviarAlServidorWS(REINCIO_ESTADOS,DISPARADOR_AUTOMATICO);
          
          Serial.println("Atendiendo peticiones y censado sensores...");
          digitalWrite(pinLEDVerde, HIGH);
          startMillis = millis();   // Tiempo de inicio para tomar mediciones
        
        } else {
          digitalWrite(pinLEDRojo, HIGH);
          Serial.println("ERROR");
          delay(60000000);
        }

      } else {
        digitalWrite(pinLEDVerde, LOW);
        digitalWrite(pinLEDRojo, HIGH);
        Serial.println("ERROR");
        delay(60000000);
      }
    } else  {
      digitalWrite(pinLEDVerde, LOW);
      digitalWrite(pinLEDRojo, HIGH);
      Serial.println("ERROR");
      delay(60000000);
    } 
}

void nada(){
  
}

void loop() {

  /* Atencion de peticiones */
  server.handleClient();    
  
  if(estadoSelfieHouse == ACTIVADO)
  {
    currentMillis = millis();   //get the current "time" (actually the number of milliseconds since the program started)
    if (currentMillis - startMillis >= TIEMPO_CENSADO)//test whether the period has elapsed
    {
      Serial.println("Tomo mediciones");
      medirSensores();  /* Si el estado de la casa está activado, obtengo datos de sensores */   
      evaluarMediciones();
      startMillis = currentMillis;  //IMPORTANT to save the start time of the current LED state.
    }
  }
  
   
  /* Si el estado de la casa está activado, evaluare las mediciones tomadas */
 
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
void activarSelfieHouseWS()
{
  Serial.println("Instruccion recibida: Estado selfieHouse ACTIVADO");
  estadoSelfieHouse = ACTIVADO;
  digitalWrite(pinLEDAzul, HIGH);
  enviarRespuesta("OK");
}

void desactivarSelfieHouseWS()
{
  Serial.println("Instruccion recibida: Estado selfieHouse DESACTIVADO");
  estadoSelfieHouse = DESACTIVADO;
  digitalWrite(pinLEDAzul, LOW);
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
  Serial.println("Instruccion recibida: Modo DEBUG OFF");
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
  String contenido = "GET /selfieHouse/ws/ArduinoReceiverWs?accion=";
  contenido += accion;
  contenido += "&disparador=";
  contenido += disparador;
  modoEjecucion == MODO_DEBUG ? Serial.println(contenido) : false;
  client.println(contenido);
  
}

void enviarRespuesta(String respuesta)
{
  server.send(200, "text/plain", respuesta);
}

bool iniciarSensores()
{
  /* Inicializo Buzzer */
  pinMode(pinBuzzer, OUTPUT);
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
  
  // LUZ
  double valor = analogRead(pinSensorLuz); 
  medicionLuz = ((1023-valor) * 10 /valor);
  
  // FLAMA
  medicionFlama = digitalRead(pinSensorFlama);
  
  // MOVIMIENTO
  
  digitalRead(pinSensorMovimiento) == HIGH ? estadoMovimiento = ACTIVADO : estadoMovimiento = DESACTIVADO;
  
  // TEMPERATURA
  medicionTemperatura = sensorTempyHum.readTemperature();
   
   
  //Serial.print(mostrarMediciones());

}

bool evaluarMediciones ()
{
  /* Evaluo por orden de importancia */

  //Serial.print("\nNivel de luz: ");
  //Serial.println(medicionLuz);
  if( medicionLuz > -3 && medicionLuz < 1000){
    //Serial.println("Bajo");
    
  } else if (medicionLuz >= 1000 && medicionLuz<2000){
    //Serial.println("Medio");
  }
  
  else {
    //Serial.println("Alto");
    //Serial.println("Se analiza el nivel de flama...");
    //Serial.print("Nivel de flama: ");
    
    if (medicionFlama == LOW)
    {
      // No hay fuego
      //Serial.println("Normal");

    } else {

      // Activo el Buzzer e informo al servidor Apache
      //Serial.println("FUEGO!");
      estadoBuzzer = ACTIVADO;
      activarBuzzer();
      digitalWrite(pinLEDRojo, HIGH);

    if (estadoFlama == DESACTIVADO)
    {
      estadoFlama = ACTIVADO;
      enviarAlServidorWS(BUZZER_ACTIVADO, DISPARADOR_FLAMA);
    } else {
      // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
    }

    }
  }
  
  //Serial.print("Detección de movimiento: ");
  /*
    medicionMovimiento = Medicion instantanea del movimiento del sensor
    estadoMovimiento = FLAG que determina si el controlador ya esta alertado que hubo movimiento. Se usa para no lanzar la alarma dos veces en caso que el movimiento no cese.
  */
  if (medicionMovimiento == DESACTIVADO && estadoMovimiento == DESACTIVADO)
  {
    //Serial.println("No se detectó movimiento");
    //digitalWrite(pinLEDRojo, LOW);
  } else {

    Serial.println("Se detectó movimiento");
    estadoBuzzer = ACTIVADO;
    activarBuzzer();
    digitalWrite(pinLEDRojo, HIGH);

    if (estadoMovimiento == DESACTIVADO)
    {
      modoEjecucion == MODO_DEBUG ? Serial.println("Aviso al servidor Apache") : false;
      estadoMovimiento = ACTIVADO;
      enviarAlServidorWS(BUZZER_ACTIVADO, DISPARADOR_MOVIMIENTO);
    } else {
      // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
    }

  }

  //Serial.print("Se evalua la temperatura y humedad: ");
 // Serial.print("Temperatura: ");
 // Serial.println(medicionTemperatura);
  if (medicionTemperatura > TOPE_TEMPERATURA)      // Si medicionTemperatura es NaN entonces medicionTemperatura != medicionTemperatura
  { 
      if(isnan(medicionTemperatura) == 0){      
        // Serial.println(medicionTemperatura);
        if (estadoVentilador == DESACTIVADO) {
          modoEjecucion == MODO_DEBUG ? Serial.println("Temperatura excedida. Se enciende ventilador") : false;
          estadoVentilador = ACTIVADO;
          activarVentilador();
          // Avisar al Apache
        } else {
          //Serial.println("Temperatura excedida. El ventilador ya esta encendido");
    
        }
   
        if (estadoTyH == DESACTIVADO)
        {
          modoEjecucion == MODO_DEBUG ? Serial.println("Temperatura excedida. Aviso al servidor Apache") : false;
          estadoTyH = ACTIVADO;
          enviarAlServidorWS(VENTILADOR_ACTIVADO, DISPARADOR_TEMPERATURA);
        } else {
          // Si esta activado es porque avise, entonces no voy a matar al servidor enviandole lo mismo 50 veces
        }
     }
   
  } else {
     
       //Serial.println("Temperatura en rango aceptable");  

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
    for (int pos = 0; pos <= 90; pos += 1) // Va de los 0 grados a los 90 grados
    { // ...en saltos de 1 grado
      servoTrabaPuerta.write(pos);     // Le digo al servo que vaya a la posicion a traves de la variable "pos"
      delay(15);                       // Espera 15ms para que el servo alcance la posicion
    }
    estadoTraba = ACTIVADO;
  } else {
    modoEjecucion == MODO_DEBUG ? Serial.println("La puerta ya esta trabada") : false;
  }
}

void destrabarPuerta()
{

  if (estadoTraba == ACTIVADO)
  {
    for (int pos = 90; pos >= 0; pos -= 1) // Va de los 90 grados a los 0 grados
    {
      servoTrabaPuerta.write(pos);      // Le digo al servo que vaya a la posicion a traves de la variable "pos"
      delay(15);                       // Espera 15ms para que el servo alcance la posicion
    }
    estadoTraba = DESACTIVADO;
  } else {
    modoEjecucion == MODO_DEBUG ? Serial.println("La puerta ya esta destrabada") : false;
    
  }
}

void parpadearLed(int pin)
{
  digitalWrite(pin, LOW);
  delay(TIEMPO_PARPADEO);
  digitalWrite(pin, HIGH);
}


void activarBuzzer()
{
  estadoBuzzer = ACTIVADO;
  tone(pinBuzzer, 500, 1000);
  tone(pinBuzzer, 1000, 1000);
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
  medirSensores();
  const size_t bufferSize = JSON_ARRAY_SIZE(2) + JSON_OBJECT_SIZE(5) + 60;
  DynamicJsonBuffer jsonBuffer(bufferSize);

  JsonObject& json = jsonBuffer.createObject();

  json["temperatura"] = medicionTemperatura;                 
  estadoMovimiento == ACTIVADO ? json["movimiento"]= "Hay movimiento" : json["movimiento"]= "No hay movimiento";
  
  if( medicionLuz > 10 && medicionLuz < 1000){
    json["luz"] = "Bajo";
  } else if (medicionLuz >= 1000 && medicionLuz < 2000){
    json["luz"] = "Medio";
  } else {
    json["luz"] = "Alto";
  }
  
  medicionFlama == HIGH ? json["flama"] =  "Hay fuego" : json["flama"] = "No hay fuego";

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
  
  estadoSelfieHouse == ACTIVADO ? json["selfiehouse"] = "Activado" : json["selfiehouse"] = "Desactivado";
  modoEjecucion == MODO_DEBUG ? json["debug"] = "Activado" : json["debug"] = "Desactivado";
  estadoTraba == ACTIVADO ? json["puerta"] = "Trabada" : json["puerta"] = "Destrabada";
  estadoBuzzer == ACTIVADO ? json["buzzer"] = "Encendido" : json["buzzer"] = "Apagado";
  estadoVentilador == ACTIVADO ? json["ventilador"] = "Encendido" : json["ventilador"] = "Apagado";
  digitalRead(pinLEDRojo) == HIGH ? json["ledrojo"] = "Encendido" : json["ledrojo"] = "Apagado"; 
  digitalRead(pinLEDVerde) == HIGH ? json["ledverde"] = "Encendido" : json["ledverde"] = "Apagado"; 
 
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
