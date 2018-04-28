/***********************************************************************************
 * Función setup()
 * Descripción: Se ejecuta una vez al iniciar el dispositivo.
 * @Mauro (21/4/18): Por ahora pongo parte del pseudocodigo
 * **********************************************************************************
*/
void setup()
{
	Serial.println("\n\n\nInicio servidor Serial... ");
	Serial.begin(115200);
	
	
	definirPinesDeEntradaYSalida();		// Defino los pines que seran utilizados como sensores / actuadores.
	
	if(conectarAWIFI())			
	{     
	  iniciarWebServer();	  
    } 
	else 
	{
      Serial.println("\n\n\nFallo el Wifi revisar conexion");     
    }
	
	
}

/***********************************************************************************
 * Función loop()
 * Descripción: Se ejecuta de forma infinita luego de ejecutar el setup.
 * **********************************************************************************
*/	
void loop()
{
	server.handleClient();		// Atencion de peticiones
	censarSensores();
	evaluoCensados();
	
}