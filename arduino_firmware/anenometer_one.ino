#include <Wire.h>

volatile unsigned int cnt = 0;
volatile int last_int = 0;

void an_signal() {
  int last_time = millis() - last_int;
  if (last_time > 30) {
    cnt = cnt + 1;
    last_int = millis();
  }
}


void setup() {
  Serial.begin(9600);
  attachInterrupt(0, an_signal, CHANGE);
  Wire.begin(0x04);             // join i2c bus as slave
  Wire.onRequest(requestEvent); // register event
  Serial.println("Finished initialization");
}

void loop() {
  Serial.println(cnt);
  delay(1000);
}

void requestEvent()
{
  int cnt_out = cnt;
  cnt = 0;
  byte hl[2];
  hl[0] = lowByte(cnt_out);
  hl[1] = highByte(cnt_out);
  Wire.write(hl,2);
}

