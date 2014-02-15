#include <Ethernet.h>
#include <SPI.h>
#include <utility/w5100.h>

#include "DHT.h"  // temp ad humidity sensor

#define DHTTYPE DHT22   // DHT22  (AM2302)
#define DHTPIN  7       // DHT22 signal pin

DHT dht(DHTPIN, DHTTYPE);


char token[] = "hacklab";
char server[] = "knotsup.ibored.com.au";

// IPAddress ip(10,0,0,38);
// IPAddress myDns(10,0,0,138);
// IPAddress myGW(10,0,0,138);

IPAddress ip(10,38,38,38);
IPAddress myDns(10,38,38,1);
IPAddress myGW(10,38,38,1);

byte mac[] = {
  0xDA, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};

EthernetClient client;

void setup() {
  Serial.begin(9600);
  // give the ethernet module time to boot up:

  Ethernet.begin(mac, ip, myDns, myGW);
  W5100.setRetransmissionTime(0x1388);
  W5100.setRetransmissionCount(3);

  dht.begin();  // humidity and temperature

  delay(1000);
  Serial.print("My IP address: ");
  Serial.println(Ethernet.localIP());
}

void loop() {
  if (client.available()) {
    char c = client.read();
    Serial.print(c);
  }
  float h = dht.readHumidity();
  float t = dht.readTemperature();
  if (isnan(t) || isnan(h)) {
    Serial.println("Failed to read from DHT");
  } 
  else {
    send_data('humidity',h);
    send_data('temp'    ,t);
  }
}

void send_data(char key, float val) {
  if (client.connect(server, 80)) {
    //    float val = 100.0 / 1024.0 * analogRead(TEST_PIN);
    Serial.print(val);
    Serial.println(" <- sending...");
    client.print("GET /put_get.php?key=");
    client.print(key);
    client.print("&val=");
    client.print(val);
    client.print("&token=");
    client.print(token);
    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(server);
    client.println();
  } 
  else {
    Serial.print("Unable to connect to ");
    Serial.println(server); 
    client.stop();
  }
}





