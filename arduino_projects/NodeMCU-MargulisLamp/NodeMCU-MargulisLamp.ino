/*
Автор: Сергей Галочкин
email: decole@rambler.ru
Данный скетч для NodeMCU.
*/

// #include <Arduino.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>

// Update these with values suitable for your network.

const char* ssid = "DECOLE-NET";
const char* password = "A9061706210";
const char* mqtt_server = "192.168.1.5";
#define LAMP 5 // D1 = 5 io - https://www.instructables.com/id/NodeMCU-ESP8266-Details-and-Pinout/
WiFiClient espClient;
PubSubClient client(espClient);
long lastMsg = 0;
char msg[50];
int value = 0;


void setup_wifi() {

  delay(10);
  // We start by connecting to a WiFi network
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  randomSeed(micros());

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

}

void callback(char* topic, byte* payload, unsigned int length) {  
  String topicValue = "";
  for (int i = 0; i < length; i++) {
    topicValue += (char)payload[i];
  }
  if(topic = "margulis/lamp01"){
    if(String(topicValue).indexOf("on") >= 0) {
      Serial.println("r-on");
      digitalWrite(LAMP, LOW);
      topicValue = "";      
    }
    else if(String(topicValue).indexOf("off") >= 0) {
      Serial.println("r-off");
      digitalWrite(LAMP, HIGH);
      topicValue = "";  
    }
  }

}

void reconnect() {
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Create a random client ID
    String clientId = "ESP8266Client-";
    clientId += String(random(0xffff), HEX);
    // Attempt to connect
    if (client.connect(clientId.c_str()), "esp", "esp99669966q") {
      Serial.println("connected");
      // Once connected, publish an announcement...
      client.publish("outTopic", "margulis lamp");
      // ... and resubscribe
      client.subscribe("margulis/lamp01");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      // Wait 5 seconds before retrying
      delay(5000);
    }
  }
}

void setup() {
  pinMode(BUILTIN_LED, OUTPUT);     // Initialize the BUILTIN_LED pin as an output
  Serial.begin(115200);
  setup_wifi();
  client.setServer(mqtt_server, 1883);
  client.setCallback(callback);
  pinMode(LAMP,OUTPUT);
  digitalWrite(LAMP, HIGH);
}

void loop() {

  if (!client.connected()) {
    reconnect();
  }

  client.loop();
  long now = millis();

  if (now - lastMsg > 10000) {    
    client.publish("margulis/check/lamp01",  String(!digitalRead(LAMP)).c_str(), true); // прихожка
    lastMsg = now;
  }

}
