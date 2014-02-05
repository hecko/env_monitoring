volatile unsigned int cnt = 0;
volatile unsigned int last_int = 0;
volatile unsigned int lightPin = 0; // pin for photo resistor
volatile unsigned int lightIntensity = 0;

void an_signal() {
  int last_time = millis() - last_int;
  if (last_time > 30) {
    cnt = cnt + 1;
    last_int = millis();
  }
}

void setup() {
  pinMode(lightPin, INPUT);
  Serial.begin(9600);
  attachInterrupt(0, an_signal, CHANGE);
}

void loop() {  
  if (Serial.available() > 0) {
    char req = Serial.read();
    Serial.print(req);
    Serial.print(":");
    switch(req) {
    case '0': 
      test(); 
      break;
    case '1': 
      anemometer(); 
      break;
    case '2': 
      light(); 
      break;
    default:
      Serial.print("unknown command\n");  
      break;
    }
  }
}

void test() {
  Serial.print("test:123\n");
}

void anemometer() {
  int cnt_out = cnt;
  cnt = 0;
  Serial.print("anemometer_cnt:");
  Serial.print(cnt_out);
  Serial.print("\n");
}

void light() {
  lightIntensity = analogRead(lightPin);
  Serial.print("light:");
  Serial.print(lightIntensity);
  Serial.print("\n");
}




