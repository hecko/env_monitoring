#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BMP085_U.h>
#include "DHT.h"

Adafruit_BMP085_Unified bmp = Adafruit_BMP085_Unified(10085);

#define WV_PIN  A3 //wind vane potencioneter sensor
#define LED_PIN 4
#define DHTPIN  7  //DHT temp + humidity sensor
#define DHTTYPE DHT22   // DHT 22  (AM2302)

DHT dht(DHTPIN, DHTTYPE);

volatile unsigned int cnt = 0;
volatile unsigned int last_int = 0;
volatile unsigned int lightPin = 0; // pin for photo resistor
volatile unsigned int lightIntensity = 0;
volatile char req;

void an_signal() {
  int last_time = millis() - last_int;
  if (last_time > 30) {
    cnt = cnt + 1;
    last_int = millis();
  }
}

void setup() {
  pinMode(lightPin, INPUT);
  pinMode(LED_PIN, OUTPUT);
  pinMode(WV_PIN, INPUT);
  Serial.begin(9600);
  attachInterrupt(0, an_signal, CHANGE);
//  sensors.begin();
  dht.begin();  // humidity and temperature
  digitalWrite(LED_PIN, HIGH);
  delay(1000);
  digitalWrite(LED_PIN, LOW);
  bmp.begin(); // barometric pressure and temperature
}

void loop() {
  if (Serial.available() > 0) {
    req = Serial.read();
    switch(req) {
    case '0':
      anemometer_reset();
      break;
    case '1':
      anemometer();
      break;
    case '2':
      light();
      break;
    case '3':
//      temp();
      break;
    case '4':
      wv();
      break;
    case '5':
      dht_temp_humid();
      break;
    case '6':
      pressure();
      break;
    default:
      Serial.print("unknown command\n");
      break;
    }
  }
}

void anemometer() {
  int cnt_out = cnt;
  cnt = 0;
  Serial.print(req);
  Serial.print(":");
  Serial.print("anemometer_cnt:");
  Serial.print(cnt_out);
  Serial.print("\n");
}

void anemometer_reset() {
  cnt = 0;
}

void light() {
  lightIntensity = analogRead(lightPin);
  Serial.print(req);
  Serial.print(":");
  Serial.print("light:");
  Serial.print(lightIntensity);
  Serial.print("\n");
}

void wv() { //wind_vane
  int wv = analogRead(WV_PIN);
  Serial.print(req);
  Serial.print(":");
  Serial.print("wind_vane:");
  Serial.print(wv);
  Serial.print("\n");
}

//void temp() {
//  sensors.requestTemperatures();
//  delay(1000);
//  Serial.print(req);
//  Serial.print(":");
//  Serial.print("temp:");
//  Serial.print(sensors.getTempCByIndex(0));
//  Serial.print("\n");
//}

void dht_temp_humid() {
  // Reading temperature or humidity takes about 250 milliseconds!
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  float h = dht.readHumidity();
  float t = dht.readTemperature();  
  // check if returns are valid, if they are NaN (not a number) then something went wrong!
  if (isnan(t) || isnan(h)) {
    Serial.println("Failed to read from DHT");
  } 
  else {
    Serial.print(req);
    Serial.print(":");
    Serial.print("temp:");
    Serial.print(t);
    Serial.print(":humid:");
    Serial.print(h);
    Serial.print("\n");
  } 
}

void pressure() {
  sensors_event_t event;
  bmp.getEvent(&event);
  delay(300);
  Serial.print(req);
  Serial.print(":");
  Serial.print("pressure:");
  Serial.print(event.pressure);
  Serial.print("\n");
}
