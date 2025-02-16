//BIBLIOTECAS
#include <DHT.h>
#include <NTPClient.h>
#include <WiFiUdp.h> //Pré-instalada com o Arduino IDE
#include <TimeLib.h>
#define DHTPIN 0 // Pin Digital onde está ligado o sensor
#define DHTTYPE DHT11 // Tipo de sensor DHT
DHT dht(DHTPIN, DHTTYPE); // Instanciar e declarar a class DHT
#include <WiFi101.h>
#include <ArduinoHttpClient.h>

WiFiUDP clienteUDP;
//Servidor de NTP do IPLeiria: ntp.ipleiria.pt
//Fora do IPLeiria servidor: 0.pool.ntp.org
char NTP_SERVER[] = " 0.pool.ntp.org";
NTPClient clienteNTP(clienteUDP, NTP_SERVER, 3600);
char SSID[] = "labs_lca";
char PASS_WIFI[] = "robot1cA!ESTG";
char URL[] = "10.79.12.165";
int PORTO = 80; // ou outro porto que esteja definido no servidor

//PINOS
const int photocellPin = A0;
const int redPin = 11;  // R petal on RGB LED module connected to digital pin 11
const int greenPin = 10;  // G petal on RGB LED module connected to digital pin 10
const int bluePin = 9;  // B petal on RGB LED module connected to digital pin 9
int buzzer = 8;//the pin of the active buzzer ANTES ESTAVA O 11

//definir os nomes
String enviaNomeTemp = "temperatura";
String enviaNomeHum = "humidade";
String enviaNomeLum = "luminosidade";
String enviaNomeSem = "semaforo";
String enviaNomeLed = "led";
String enviaNomeBuz = "buzzer";

//criar as variaveis para receber os valores
float enviaTemVal = 27;
float enviaHumVal = 29;
float enviaLumVal = 23;
float enviaLedVal = 1;
float recebeBuzVal = 0;
float recebeSemVal = 0;

String Led1 = "ON";

WiFiClient clienteWifi;
HttpClient clienteHTTP = HttpClient(clienteWifi, URL, PORTO);

void setup() {
  //Serial.begin(9600); //TIRAR O COMENTARIO
  //definir os pinos como entradas/saidas
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(redPin, OUTPUT); // sets the redPin to be an output
  pinMode(greenPin, OUTPUT); // sets the greenPin to be an output
  pinMode(bluePin, OUTPUT); // sets the bluePin to be an output
  pinMode(buzzer, OUTPUT); //initialize the buzzer pin as an output

  while (!Serial)
    WiFi.begin();
  while (WiFi.status() != WL_CONNECTED)
  {
    Serial.print(".");
    delay(500);
  }
  dht.begin();
}

//funcao data a tempo real
void update_time(char *datahora) {
  clienteNTP.update();
  unsigned long epochTime = clienteNTP.getEpochTime();
  sprintf(datahora, "%02d-%02d-%02d %02d:%02d:%02d", year(epochTime), month(epochTime), day(epochTime), hour(epochTime), minute(epochTime), second(epochTime));
}

//funcao para fazer o post 
void post2API(String nome, float valor, String hora)
{

  String URLPath = "/ti/api/api.php"; //altere o grupo
  String contentType = "application/x-www-form-urlencoded";
  String body = "";
  if ( nome == enviaNomeLed)
  {
    Serial.println(valor);
    if (valor = 1)
    {
      Led1 = "ON";
    }
    else
    {
      Led1 = "OFF";
    }
    body = "nome=" + nome + "&valor=" + Led1 + "&hora=" + hora; //Fizemos dois bodys diferentes para enviar ON OFF em vez de 0 1
  }
  else
  {
    body = "nome=" + nome + "&valor=" + valor + "&hora=" + hora;
  }
  Serial.println(body);


  clienteHTTP.post(URLPath, contentType, body);

  while (clienteHTTP.connected())
  {
    if (clienteHTTP.available())
    {
      int codigoestado =  clienteHTTP.responseStatusCode();
      String resposta = clienteHTTP.responseBody();
      Serial.println("Status Code: " + String(codigoestado) + " Resposta: " + resposta);

    }
  }

}

void loop() {

  // Realiza a requisição GET SEMAFORO
  clienteHTTP.get("/ti/api/api.php?nome=semaforo"); 
  while (clienteHTTP.connected())
  {
    if (clienteHTTP.available())
    {
      int codigoestado =  clienteHTTP.responseStatusCode();
      String resposta = clienteHTTP.responseBody();
      recebeSemVal = resposta.toInt(); // valorGETsem1 = response;
      Serial.print("Semaforo :"); 
      Serial.println(recebeSemVal); 
     

    }
  }


 // Realiza a requisição GET SEMAFORO
  clienteHTTP.get("/ti/api/api.php?nome=alarme"); 
  while (clienteHTTP.connected())
  {
    if (clienteHTTP.available())
    {
      int codigoestado =  clienteHTTP.responseStatusCode();
      String resposta = clienteHTTP.responseBody();
      recebeBuzVal = resposta.toInt(); // valorGETsem1 = response;
      Serial.print("alarme :"); 
      Serial.println(recebeBuzVal); 
     

    }
  }

 //Leitura dos sensores
  enviaTemVal = dht.readTemperature();
  enviaHumVal = dht.readHumidity();
  float ValorLuminosidade = analogRead(photocellPin);


  String enviaHora = "2023-04-21 12:32:00";
  // Serial.println("temp:");
  //Serial.println(enviaTemVal);

  char datahora[20];
  //Serial.println("hum:");
  // Serial.println(enviaHumVal);

  update_time(datahora);

  // Serial.print("Data Atual: ");
  // Serial.println(enviaHumVal);

Serial.println("ValorLuminosidade:");

Serial.println(ValorLuminosidade);

//se tiver pouca luz  
  if (ValorLuminosidade > 500)
  {
    enviaLumVal = 0;
    enviaLedVal = 0;
  }
  else
  {
    enviaLumVal = 1;
    enviaLedVal = 1;
  }



//POSTS 
  //TEMPERATURA
  //post2API(enviaNomeTemp, enviaTemVal , datahora);
  //HUMIDADE
  //post2API(enviaNomeHum, enviaHumVal, datahora);
  //LUMINOSIDADE
  // post2API(enviaNomeLum, enviaLumVal, datahora);
  //LED
  post2API(enviaNomeLed , enviaLedVal, datahora);

  if (recebeSemVal == 1)
  {
    color(255, 0, 0); // turn the RGB LED green
    Serial.println("verde");
  }
  else
  {
    color(0, 255, 0); // turn the RGB LED red
   // Serial.println("vermelho");
  }

  if (recebeBuzVal == 1)
  {
    digitalWrite(buzzer, HIGH);
   // Serial.println("alarme on");
  }
  else
  {
    digitalWrite(buzzer, LOW);
   // Serial.println("alarme off");
  }

  delay(5000);

}
void color (unsigned char red, unsigned char green, unsigned char blue)     // the color generating function
{
  analogWrite(redPin, red);
  analogWrite(bluePin, blue);
  analogWrite(greenPin, green);
}
