#include <DHT.h>
#include <ESP8266WebServer.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266mDNS.h>
#include <Servo.h> 

#define DHTTYPE DHT22
#define TOPE_LLAMA  500
#define CANTIDAD_INTENTOS_CONEXION 3
#define TIMEOUT_CONEXION 10


/* Pines digitales */
int pinSensorTemperatura = 5;     // DEFINIR
int pinSensorMovimiento = 4;      // DEFINIR
int pinVentilador = 9999;         // DEFINIR
int pinServo = 9998;
int pinBuzzer = 4;


/* Pines analogicos */
int pinSensorLlama = A0;

DHT sensorTemperatura(pinSensorTemperatura,DHTTYPE);
Servo servoTrabaPuerta;


float medicionTemperatura, medicionHumedad, medicionSensacionTermica;
int medicionLlama;

ESP8266WebServer server(80);    // Webserver
ESP8266WiFiMulti WiFiMulti;     // Responder de peticiones
int timeoutConexion = 10 ;      // 5 segundos para conectarse al Wifi

const char* ssid = "SOa-IoT";
const char* password = "laboratorio";

void setup() {
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
      } else {
        Serial.println("ERROR");
      }
      delay(300);
    }
    else 
    {
      Serial.println("ERROR");
<<<<<<< HEAD
      }
	  
	 
=======
    }
>>>>>>> 9d85a459aa1af47d8a955ed487893cfe72063d7a
  
}

void loop() {
  server.handleClient();    // Atencion de peticiones
  //medirSensores();
  //evaluarMediciones();      // Al evaluar se activaran los flags de alarmas y trabas
  
}


/*
 * Función conectarAWIFI()
 * Autor: @mauroat
 * Descripción: Intenta realizar una conexión Wifi segun las credenciales establecidas.
 * Devuelve true si la conexión es exitosa, sino retorna false.
 * El tiempo de intento de conexión es de X segundos.
 * Ultima modificacion: 28/4/2018 12:12
 * 
*/
boolean conectarAWIFI()
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
 * Autor: @mauroat
 * Descripción: Prepara las instrucciones esperadas y switchea las posibles variantes con otras funciones
 * Ultima modificacion: 28/4/2018 12:13
 * 
*/
bool iniciarWebserver()
{
  MDNS.begin("esp8266");
  /*if (MDNS.begin("esp8266")) {
    Serial.println("MDNS responder iniciado");
  }*/
   
  /* Realiza una medicion de sensor y devuelve los datos por pantalla */
  server.on("/test", funcionTest);
    
    
  /* Excepcion ante una peticion no reconocida*/
   server.onNotFound(handleNotFound);

    
  /* Inicio el Webserver*/
    
  server.begin();
  // Enciendo un led de encendido
  //digitalWrite(pinAuxiliar, HIGH);
  delay(300);

  return true;
}



bool iniciarSensores()
{
  /* Inicializo Alarma */
  pinMode(pinBuzzer, OUTPUT);

  /* Inicializo Sensor de Temperatura */
  // Inicializado como variable global
  
  /* Inicializo Sensor de Movimiento */
  // No es necesario

  /* Inicializo ventilador */
   pinMode(pinVentilador, OUTPUT);
  
}


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
//    return contenido;
    
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


void medirTemperaturaYHumedad()
{
  float medicionHumedad = sensorTemperatura.readHumidity();
  Serial.print("Humedad:" );
  Serial.println(medicionHumedad);
  float medicionTemperatura = sensorTemperatura.readTemperature();
  Serial.print("Temperatura:" );
  Serial.println(medicionTemperatura);
  float medicionSensacionTermica = sensorTemperatura.computeHeatIndex(medicionTemperatura, medicionHumedad, false);
  Serial.print("Sensacion termica:" );
  Serial.println(medicionSensacionTermica);
}

int medirLlama()
{
  int medicion = analogRead(pinSensorLlama);
  Serial.print("Llama:" );
  Serial.println(medicion);
  return medicion;
}

void sonarAlarma(){

  tone(pinBuzzer,500,1000);
  delay(1000);
  tone(pinBuzzer,1000,1000);
  delay(1000);
  
}

void inicializarSalidas(){

 pinMode(pinBuzzer, OUTPUT);   // sets the pin as output
 analogWrite(pinBuzzer,150);   
}