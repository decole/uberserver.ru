/*
Автор: Сергей Галочкин
email: decole@rambler.ru

подписные топики

- home/ralay01              - реле 01
- home/restroom/temperature - уборная
- home/kitchen/temperature  - кухня
- home/hall/temperature     - зал

*/
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <OneWire.h>

#define D5 14 // for relay
#define D6 12 // for relay

const char* ssid = "DECOLE-NET";
const char* password = "A9061706210";
const char* mqtt_server = "192.168.1.5";
const char* mqttUser = "node1";
const char* mqttPassword = "99669966q";

const int nsensors = 3;
byte sensors[][8] = {
  { 0x28, 0xA5, 0x17, 0x79, 0x97, 0x14, 0x03, 0xD8 },
  { 0x28, 0xE0, 0x43, 0x79, 0x97, 0x14, 0x03, 0xCC },
  { 0x28, 0xB0, 0x1F, 0x79, 0x97, 0x13, 0x03, 0x38 }
};
int16_t tempraw[nsensors];

OneWire  ds(5);  // on pin 1 (a 4.7K pullup is necessary)
WiFiClient espClient;
PubSubClient client(espClient);
long lastMsg = 0;
char msg[50];


// begin string to array in loop
#define INPUT_SIZE 55

void setup() {
  
  pinMode(D5, OUTPUT);
  pinMode(D6, OUTPUT);
  digitalWrite(D5, HIGH); // off relay on start
  digitalWrite(D6, HIGH); // off relay on start
  
  Serial.begin(115200);
  
  setup_wifi();
  client.setServer(mqtt_server, 1883); // 8083 1883 9001
  client.setCallback(callback);  
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
  String topicValue = "";
  for (int i = 0; i < length; i++) {
    topicValue += (char)payload[i];
  }
  if(String(topic).indexOf("home/ralay01") >= 0){
    if(String(topicValue).indexOf("on") >= 0) {
      digitalWrite(D5, LOW);
      topicValue = "";      
    }
    else if(String(topicValue).indexOf("off") >= 0) {
      digitalWrite(D5, HIGH);
      topicValue = "";  
    }
  }
  // для котла, стандарт включено при реле в положение отключено
  // для уменьшения износа реле. т.к. планируется держать котел включенным
  if(String(topic).indexOf("home/ralay02") >= 0){
    if(String(topicValue).indexOf("on") >= 0) {
      digitalWrite(D6, LOW);
      topicValue = "";      
    }
    else if(String(topicValue).indexOf("off") >= 0) {
      digitalWrite(D6, HIGH);
      topicValue = "";  
    }
  }
}

void reconnect() {  
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Attempt to connect
    if (client.connect("ESP8266HOME")) {
      Serial.println("connected");
      // Once connected, publish an announcement...
      client.publish("outTopic", "homecontroller");
      client.subscribe("home/ralay01");
      client.subscribe("home/ralay02");
      // ... and resubscribe
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
  ds18process();
  
  long now = millis();
  if (now - lastMsg > 10000) {
    
    float s1 = ds18temp(0, tempraw[0]);
    float s2 = ds18temp(0, tempraw[1]);
    float s3 = ds18temp(0, tempraw[2]);
    
    Serial.print("S1: ");
    Serial.println(s1);
    Serial.print("S2: ");
    Serial.println(s2);
    Serial.print("S3: ");
    Serial.println(s3);
    
    client.publish("home/restroom/temperature",  String(s1).c_str(), true); 
    client.publish("home/kitchen/temperature",  String(s2).c_str(), true);
    client.publish("home/hall/temperature",  String(s3).c_str(), true);
    client.publish("home/check/ralay01",  String(!digitalRead(D5)).c_str(), true);
    client.publish("home/check/ralay02",  String(!digitalRead(D6)).c_str(), true);
    
    lastMsg = now;
  }
  
}

/* Process the sensor data in stages.
 * each stage will run quickly. the conversion 
 * delay is done via a millis() based delay.
 * a 5 second wait between reads reduces self
 * heating of the sensors.
 */
void ds18process() {
  static byte stage = 0;
  static unsigned long timeNextStage = 0;
  static byte sensorindex = 100;
  byte i, j;
  byte present = 0;
  byte type_s;
  byte data[12];
  byte addr[8];

  if(stage == 0 && millis() > timeNextStage) {
    if (!ds.search(addr)) {
      //no more, reset search and pause
      ds.reset_search();
      timeNextStage = millis() + 5000; //5 seconds until next read
      return;
    } else {
      if (OneWire::crc8(addr, 7) != addr[7]) {
        Serial.println("CRC is not valid!");
        return;
      }
      //got one, start stage 1
      stage = 1;
    }
  }
  if(stage==1) {
    Serial.print("ROM =");
    for ( i = 0; i < 8; i++) {
      Serial.write(' ');
      Serial.print(addr[i], HEX);
    }
    Serial.println(' ');
    //find sensor
    for(j=0; j<nsensors; j++){
      sensorindex = j;
      for(i=0; i<8; i++){
        if(sensors[j][i] != addr[i]) {
          sensorindex = 100;
          break; // stop the i loop
        }
      }
      if (sensorindex < 100) { 
        break; //found it, stop the j loop
      }
    }
    if(sensorindex == 100) {
      //Serial.println("  Sensor not found in array");
      stage = 0;
      return;
    }
    //Serial.print("  index="); Serial.println(sensorindex);
  
    ds.reset();
    ds.select(sensors[sensorindex]);
    ds.write(0x44, 0);        // start conversion, with parasite power off at the end
    stage = 2; //now wait for stage 2
    timeNextStage = millis() + 1000; //wait 1 seconds for the read
  }
  
  if (stage == 2 && millis() > timeNextStage) {
    // the first ROM byte indicates which chip
    switch (sensors[sensorindex][0]) {
      case 0x10:
        //Serial.print("  Chip = DS18S20");  // or old DS1820
        //Serial.print("  index="); Serial.println(sensorindex);
        type_s = 1;
        break;
      case 0x28:
        //Serial.print("  Chip = DS18B20");
        //Serial.print("  index="); Serial.println(sensorindex);
        type_s = 0;
        break;
      case 0x22:
        //Serial.print("  Chip = DS1822");
        //Serial.print("  index="); Serial.println(sensorindex);
        type_s = 0;
        break;
      default:
        Serial.println("Device is not a DS18x20 family device.");
        stage=0;
        return;
    }
  
    present = ds.reset();
    ds.select(sensors[sensorindex]);
    ds.write(0xBE);         // Read Scratchpad
  
    //Serial.print("  Data = ");
    //Serial.print(present, HEX);
    //Serial.print(" ");
    for ( i = 0; i < 9; i++) {           // we need 9 bytes
      data[i] = ds.read();
      //Serial.print(data[i], HEX);
      //Serial.print(" ");
    }
    Serial.print(" CRC=");
    Serial.print(OneWire::crc8(data, 8), HEX);
    //OneWire::crc8(data, 8);
    //Serial.print(" index="); Serial.print(sensorindex);
    //Serial.println();
  
    int16_t raw = (data[1] << 8) | data[0];
    if (type_s) {
      raw = raw << 3; // 9 bit resolution default
      if (data[7] == 0x10) {
        // "count remain" gives full 12 bit resolution
        raw = (raw & 0xFFF0) + 12 - data[6];
      }
    } else {
      byte cfg = (data[4] & 0x60);
      // at lower res, the low bits are undefined, so let's zero them
      if (cfg == 0x00) raw = raw & ~7;  // 9 bit resolution, 93.75 ms
      else if (cfg == 0x20) raw = raw & ~3; // 10 bit res, 187.5 ms
      else if (cfg == 0x40) raw = raw & ~1; // 11 bit res, 375 ms
      //// default is 12 bit resolution, 750 ms conversion time
    }
    tempraw[sensorindex] = raw;
    stage=0;
  }
}

/* Converts raw temp to Celsius or Fahrenheit
 * scale: 0=celsius, 1=fahrenheit
 * raw: raw temp from sensor
 * 
 * Call at any time to get the last save temperature
 */
float ds18temp(byte scale, int16_t raw) 
{
  switch(scale) {
    case 0: //Celsius
      return (float)raw / 16.0;
      break;
    case 1: //Fahrenheit
      return (float)raw / 16.0 * 1.8 + 32.0;
      break;
    default: //er, wut
      return -255;
  }
}
