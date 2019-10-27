/*
Автор: Сергей Галочкин
email: decole@rambler.ru

Данный скетч для NodeMCU.
Примерно каждые 5 секунд Ардуино отправляет данные на модуль
примерно такую сроку:
t1:0&h1:67&t2:14&h2:54&t3:15&h3:67&t4:22&h4:61
в скетче строка разделяется на ключ/значение и передается по 
топикам:
- underflor/temperature
- underflor/humidity
- underground/temperature
- underground/humidity
- holl/temperature
- holl/humidity
- margulis/temperature
- margulis/humidity
принимает с подписанного топика "margulis/relay01" данные
on - отправляет в Ардуино "r-on" по RX/TX
off - отправляет на Ардуино "r-off" по RX/TX

*/
#include <ESP8266WiFi.h>
#include <PubSubClient.h>

// Update these with values suitable for your network.

//const char* ssid = "WiFi-DOM.ru-5269";
//const char* password = "pSSff4bFZe";
//const char* mqtt_server = "192.168.0.6";

const char* ssid = "DECOLE-WIFI";
const char* password = "A9061706210";
const char* mqtt_server = "192.168.1.5";
const char* mqttUser = "node1";
const char* mqttPassword = "99669966q";
    
WiFiClient espClient;
PubSubClient client(espClient);
long lastMsg = 0;
char msg[50];

// Reley ports
int Relay1 = 5;

// begin string to array in loop
#define INPUT_SIZE 55
String request = "";

void setup() {
  pinMode(BUILTIN_LED, OUTPUT);     // Initialize the BUILTIN_LED pin as an output
  Serial.begin(115200);
  
  setup_wifi();
  client.setServer(mqtt_server, 1883); // 8083 1883 9001
  client.setCallback(callback);

  pinMode(Relay1, OUTPUT);
  
}

void setup_wifi() {

  delay(10);
  // We start by connecting to a WiFi network
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

void callback(char* topic, byte* payload, unsigned int length) {
  // Serial.print("Message arrived [");
  // Serial.print(topic);
  // Serial.print("] ");
  String topicValue = "";
  for (int i = 0; i < length; i++) {
    //Serial.print((char)payload[i]);
    topicValue += (char)payload[i];
  }
  //Serial.println("");
  if(topic = "margulis/relay01"){
    // Serial.println("find relay subscribe");
    // Serial.println(topicValue);
    if(String(topicValue).indexOf("on") >= 0) {
      Serial.println("r-on");
      topicValue = "";      
    }
    else if(String(topicValue).indexOf("off") >= 0) {
      Serial.println("r-off");
      topicValue = "";  
    }
  }
  // Serial.println(" its callback function");
  // Switch on the LED if an 1 was received as first character
  if ((char)payload[0] == '1') {
    digitalWrite(BUILTIN_LED, LOW);   // Turn the LED on (Note that LOW is the voltage level
    // but actually the LED is on; this is because
    // it is acive low on the ESP-01)
  } else {
    digitalWrite(BUILTIN_LED, HIGH);  // Turn the LED off by making the voltage HIGH
  }

}

void reconnect() {  
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Attempt to connect
    if (client.connect("ESP8266Client")) {
      Serial.println("connected");
      // Once connected, publish an announcement...
      client.publish("outTopic", "hello world");
      // ... and resubscribe
      client.subscribe("margulis/relay01");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      // Wait 5 seconds before retrying
      delay(5000);
    }
  }
}
void loop() {
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
  long now = millis();
  if (now - lastMsg > 5000) {
    //Serial.println("5 sec.");
    //if indexOf to Arduino t + h
    //Serial.println("temp");
    // make function to Serial.available()
    //delay(200);
//    client.publish("margulis/temperature", "14.2"); // var into Arduino sensor
//    Serial.println("hum");
//    // make function to Serial.available()
//    delay(100);
//    client.publish("margulis/humidity", "46"); // var into Arduino sensor
    if(Serial.available()>0){
      request = "";
      while (Serial.available() > 0){
        request = Serial.readStringUntil('\n');// '\n'
      }
      //Serial.println(request); // для отладки, убрать после
      //unsigned int lastStringLength = request.length();
      //Serial.println(lastStringLength);
      // magic string for NodeMCU
      // t1:0&h1:67&t2:14&h2:54&t3:15&h3:67&t4:22&h4:61
      // разбираем строку на параметры и значения
      int firstVal = 0; 
      int secondVal = 0;
      int separator;
      String t1,t2,t3,t4,h1,h2,h3,h4,subrequest;
      // 4 for for separate magic string
      // t1 start
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      t1=request.substring(firstVal+1, secondVal);
      //Serial.println("t1: "+t1);
      subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      //Serial.println("result = " +request.substring(firstVal+1, secondVal));
      // t1 end
      // h1 start
      request = subrequest;
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      h1=request.substring(firstVal+1, secondVal);
      //Serial.println("h1: "+h1);
      subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      // h1 end
      // t2 start
      request = subrequest;
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      t2=request.substring(firstVal+1, secondVal);
      //Serial.println("t2: "+t2);
      subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      //Serial.println("result = " +request.substring(firstVal+1, secondVal));
      // t1 end
      // h1 start
      request = subrequest;
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      h2=request.substring(firstVal+1, secondVal);
      //Serial.println("h2: "+h2);
      subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      // h2 end
      // t3 start
      request = subrequest;
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      t3=request.substring(firstVal+1, secondVal);
      //Serial.println("t3: "+t3);
      subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      //Serial.println("result = " +request.substring(firstVal+1, secondVal));
      // t3 end
      // h3 start
      request = subrequest;
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      h3=request.substring(firstVal+1, secondVal);
      //Serial.println("h3: "+h3);
      subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      // h3 end
      // t4 start
      request = subrequest;
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      t4=request.substring(firstVal+1, secondVal);
      //Serial.println("t4: "+t4);
      subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      //Serial.println("result = " +request.substring(firstVal+1, secondVal));
      // t4 end
      // h4 start
      request = subrequest;
      for (int i = 0; i < request.length(); i++) {
        if (request.substring(i, i+1) == ":") {
          firstVal = i;
        }
        if (request.substring(i, i+1) == "&") {
          secondVal = i; 
          break;
        }
        //Serial.println(i);
      }
      //Serial.println("-----");
      h4=request.substring(firstVal+1, secondVal);
      //Serial.println("h4: "+h4);
      //subrequest = request.substring(secondVal+1);
      //Serial.println("subrequest: "+subrequest);
      // h4 end
      // переводим t1... в байтовый массив
      char chart1[6];
      char chart2[6];
      char chart3[6];
      char chart4[6];

      char charh1[6];
      char charh2[6];
      char charh3[6];
      char charh4[6];

      t1.toCharArray(chart1, t1.length());
      t2.toCharArray(chart2, t2.length());
      t3.toCharArray(chart3, t3.length());
      t4.toCharArray(chart4, t4.length());
      h1.toCharArray(charh1, h1.length());
      h2.toCharArray(charh2, h2.length());
      h3.toCharArray(charh3, h3.length());
      h4.toCharArray(charh4, h4.length());

      //push in mqtt server
      client.publish("underflor/temperature", chart1);
      client.publish("underflor/humidity", charh1);
      client.publish("underground/temperature", chart2);
      client.publish("underground/humidity", charh2);
      client.publish("holl/temperature", chart3);
      client.publish("holl/humidity", charh3);
      client.publish("margulis/temperature", chart4);
      client.publish("margulis/humidity", charh4);
    }
   
    
    lastMsg = now;
  }
}
