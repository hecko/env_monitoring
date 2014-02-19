#include <Ethernet.h>
#include <SPI.h>
#include <utility/w5100.h>

#include "DHT.h"  // temp ad humidity sensor

#define DHTTYPE DHT22   // DHT22  (AM2302)
#define DHTPIN  7       // DHT22 signal pin

DHT dht(DHTPIN, DHTTYPE);

char token[] = "hacklab";
char server[] = "knotsup.ibored.com.au";

//IPAddress ip(10,0,0,38);
//IPAddress myDns(10,0,0,138);
//IPAddress myGW(10,0,0,138);

IPAddress ip(10,38,38,38);
IPAddress myDns(10,38,38,1);
IPAddress myGW(10,38,38,1);

byte mac[] = {
  0xDA, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};

EthernetClient client;

void setup() {
  Serial.begin(9600);
  // give the ethernet module time to boot up:

  if (Ethernet.begin(mac) == 0) {
    // if we dont get IP from DHCP, assign the address manually
    Serial.println("Did not get IP from DHCP - setting a static one");
    Ethernet.begin(mac, ip, myDns, myGW);
  }
  W5100.setRetransmissionTime(0x1388);
  W5100.setRetransmissionCount(3);

  dht.begin();  // humidity and temperature

  Serial.print("My IP address: ");
  Serial.println(Ethernet.localIP());
  Serial.println("Will start sending data in 10 seconds");
  delay(1000);
}

void loop() {
   if (client.available()) {
      char c = client.read();
      Serial.print(c);
  }

  client.stop();

  delay(10000);

  float h = dht.readHumidity();
  float t = dht.readTemperature(false);
  if (isnan(t) || isnan(h)) {
    Serial.println("Failed to read from DHT22 sensor");
  } 
  else {
    char t_char[10];
    char h_char[10];
    sprintf(t_char,"%f",t);
    sprintf(h_char,"%f",h);
    send_data("temp"    ,t_char);
    send_data("humidity",h_char);
  }
  
  send_data("beat", "1"); // test beat

    Serial.println("------------");
}

void send_data(char key[], char val[]) {
  Serial.print("Trying to connect to server ");
  Serial.println(server);
  if (client.connect(server, 80)) {
    Serial.print("Sending key: ");
    Serial.print(key);
    Serial.print(" value: ");
    Serial.print(val);
    Serial.print(" to server ");
    Serial.println(server);
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







