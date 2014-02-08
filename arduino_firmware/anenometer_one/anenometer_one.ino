#include <OneWire.h>
#include <DallasTemperature.h>

#define ONE_WIRE_BUS 3
#define WV_PIN A1 //wind vane potencioneter sensor
#define LED_PIN 4

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

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
  sensors.begin();
  digitalWrite(LED_PIN, HIGH);
  delay(100);
  digitalWrite(LED_PIN, LOW);
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
      temp();
      break;
    case '4':
      wv();
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

void temp() {
  sensors.requestTemperatures();
  delay(1000);
  Serial.print(req);
  Serial.print(":");
  Serial.print("temp:");
  Serial.print(sensors.getTempCByIndex(0));
  Serial.print("\n");
}
