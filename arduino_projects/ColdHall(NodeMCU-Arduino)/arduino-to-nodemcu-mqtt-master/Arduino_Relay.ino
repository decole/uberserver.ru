/*
Автор: Сергей Галочкин
email: decole@rambler.ru

Данный скетч для Arduino.
Данный модуль замеряет значения температуры и влажности
и далее отправляет по RX/TX данные на NodeMCU.
прошивка для NodeMCU есть - https://gitlab.com/decole/nodemcu-mqtt
также подписанный модуль на топик "margulis/relay01" принимает данные
on - отправляет в Ардуино "r-on"
off - отправляет на Ардуино "r-off"
Ардуино включает/отключает свое реле, расположенное на пине 4

Ардуино отправляет на NodeMCU примерно такую сроку:
t1:0&h1:67&t2:14&h2:54&t3:15&h3:67&t4:22&h4:61
*/
#include <DHT.h>

int Relay1 = 4;
String request = "";

// #define DHTPIN 8
#define DHTTYPE DHT11

DHT dht4(6, DHTTYPE); // edit to real dht11
DHT dht3(7, DHTTYPE); // edit to real dht11
DHT dht2(5, DHTTYPE); // edit to real dht11
DHT dht1(8, DHTTYPE); // edit to real dht11

void setup() {
    // initialize digital pin LED_BUILTIN as an output.
    pinMode(LED_BUILTIN, OUTPUT);
    // put your setup code here, to run once:
    pinMode(Relay1, OUTPUT);
    // start serial port
    Serial.begin(115200);
    dht1.begin();
    dht2.begin();
    dht3.begin();
    dht4.begin();    
    digitalWrite(LED_BUILTIN, LOW);
}

void loop() {
    digitalWrite(LED_BUILTIN, HIGH);
    delay(500);
    // read buffer
    if(Serial.available()>0){
      request = "";
      while (Serial.available() > 0){
        request = Serial.readStringUntil('\n');// '\n'
      }
      Serial.println(request); // для отладки, убрать после
    }    
    delay(50);    
    if(String(request).indexOf("r-on") >= 0) {
      digitalWrite(Relay1, HIGH);
      //Serial.println("Relay - on");
      request = "";
    }
    else if(String(request).indexOf("r-off") >= 0) {
      digitalWrite(Relay1, LOW);
      //Serial.println("Relay - off");
      request = "";
    }
    delay(50);
    float h1 = dht1.readHumidity();
    float t1 = dht1.readTemperature();
    float h2 = dht2.readHumidity();
    float t2 = dht2.readTemperature();
    float h3 = dht3.readHumidity();// edit to real dht11
    float t3 = dht3.readTemperature();// edit to real dht11
    float h4 = dht4.readHumidity();// edit to real dht11
    float t4 = dht4.readTemperature();// edit to real dht11
    delay(50);
    // magic string for NodeMCU
    // t1:0&h1:67&t2:14&h2:54&t3:15&h3:67&t4:22&h4:61
    
    Serial.println(
      "t1:"+String(t1)+"&h1:"+String(h1)+
      "&t2:"+String(t2)+"&h2:"+String(h2)+
      "&t3:"+String(t3)+"&h3:"+String(h3)+
      "&t4:"+String(t4)+"&h4:"+String(h4)
      );
      
    digitalWrite(LED_BUILTIN, LOW);
    delay(5000);
}
