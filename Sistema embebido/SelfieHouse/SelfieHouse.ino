#include <DHT.h>
#include <ESP8266WebServer.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266mDNS.h>

#define DHTTYPE DHT22
#define TOPE_LLAMA  500
#define CANTIDAD_INTENTOS_CONEXION 3
#define TIMEOUT_CONEXION 10

int pinSensorTemperatura = 5;
int pinSensorMovimiento = 4;
int pinSensorLlama = A0;
int pinBuzzer = 4;
DHT sensorTemperatura(pinSensorTemperatura,DHTTYPE);

float medicionTemperatura, medicionHumedad;
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

      Serial.print("Inicializando Webserver: ");
      if(iniciarWebserver()){
        Serial.println("OK!");
      } else {
        Serial.println("ERROR");
      }
      delay(300);
    }
    else {
      Serial.println("ERROR");
      }
  
}

void loop() {
  server.handleClient();
}


/***********************************************************************************
 * Función conectarAWIFI()
 * Descripción: Intenta realizar una conexión Wifi segun las credenciales guardadas.
 * Devuelve true si la conexión es exitosa, sino retorna false.
 * El tiempo de intento de conexión es de X segundos
 * **********************************************************************************
*/
boolean conectarAWIFI()
{
 /* Inicio el servidor WiFI*/
  int cantidadIntentosConexion = 0;
  
  
  do{
          
     WiFi.begin(ssid, password);
    // WiFi.config(ip, gateway, subred);
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

/***********************************************************************************
 * Función iniciarWebserver()
 * Descripción: 
 * **********************************************************************************
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
  // server.onNotFound(handleNotFound);

   //  estadoWS = VERDADERO;
    
  /* Inicio el Webserver*/
    
  server.begin();
  // Enciendo un led de encendido
  //digitalWrite(pinAuxiliar, HIGH);
  delay(300);

  return true;
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
  float h = sensorTemperatura.readHumidity();
  Serial.print("Humedad:" );
  Serial.println(h);
  float t = sensorTemperatura.readTemperature();
  Serial.print("Temperatura:" );
  Serial.println(t);
  float st = sensorTemperatura.computeHeatIndex(t, h, false);
  Serial.print("Sensacion termica:" );
  Serial.println(st);
}

int medirLlama()
{
  int medicion = analogRead(pinSensorLlama);
  Serial.print("Llama:" );
  Serial.println(medicion);
  return medicion;
}
