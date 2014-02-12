#include <SPI.h>
#include <Ethernet.h>

#define TEST_PIN A0

char token[] = "hacklab";
char server[] = "knotsup.ibored.com.au";

IPAddress ip(10,38,38,38);
IPAddress myDns(10,38,38,1);

byte mac[] = {
  0xDA, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};

EthernetClient client;

unsigned long lastConnectionTime = 0;          // last time you connected to the server, in milliseconds
boolean lastConnected = false;                 // state of the connection last time through the main loop
const unsigned long postingInterval = 6*1000;  // delay between updates, in milliseconds

void setup() {
  pinMode(TEST_PIN, INPUT);
  // start serial port:
  Serial.begin(9600);
  // give the ethernet module time to boot up:
  delay(1000);
  // start the Ethernet connection using a fixed IP address and DNS server:
  Ethernet.begin(mac, ip, myDns);
  // print the Ethernet board/shield's IP address:
  Serial.print("My IP address: ");
  Serial.println(Ethernet.localIP());
}

void loop() {
  if (client.available()) {
    char c = client.read();
    Serial.print(c);
  }

  if(!client.connected() && (millis() - lastConnectionTime > postingInterval)) {
    httpRequest();
  }

  lastConnected = client.connected();
}

void httpRequest() {
  // if there's a successful connection:
  client.connect(server, 80);
  //    float val = 100.0 / 1024.0 * analogRead(TEST_PIN);
  float val = 100.0 / 1024.0 * random(0,1024);
  Serial.println(val);
  Serial.println("connecting...");
  // send the HTTP PUT request:
  client.print("GET /put_get.php?key=light&val=");
  client.print(val);
  client.print("&token=");
  client.print(token);
  client.println(" HTTP/1.1");
  client.print("Host: ");
  client.println(server);
  client.println("User-Agent: arduino-ethernet");
  client.println("Connection: close");
  client.println();

  // note the time that the connection was made:
  lastConnectionTime = millis();
  client.stop();
}

