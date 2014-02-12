#include <Ethernet.h>
#include <SPI.h>

char token[] = "hacklab";
char server[] = "knotsup.ibored.com.au";

IPAddress ip(10,38,38,38);
IPAddress myDns(10,38,38,1);

byte mac[] = {
  0xDA, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};

EthernetClient client;

void setup() {
  Serial.begin(9600);
  // give the ethernet module time to boot up:
  delay(1000);

  Ethernet.begin(mac, ip, myDns);

  Serial.print("My IP address: ");
  Serial.println(Ethernet.localIP());
}

void loop() {
  if (client.available()) {
    char c = client.read();
    Serial.print(c);
  }
  httpRequest();
}

void httpRequest() {
  float val = 100.0 / 1024.0 * random(0,1024);
  if (client.connect(server, 80)) {
    //    float val = 100.0 / 1024.0 * analogRead(TEST_PIN);
    Serial.print(val);
    Serial.println(" <- sending...");
    client.print("GET /put_get.php?key=light&val=");
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




