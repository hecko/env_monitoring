= Environmental Monitoring and control using Arduino and Raspberry PI

Three parts
- client part (run on raspberry gathering unit
- server part (run on the cloud server)
- arduino firmware (run on arduino or other avr mcu)


== Server 

=== Prerequisities

* mongodb database (mongod running)
* node package manager (npm)

=== Running

```bash
sudo npm install n -g
sudo n stable
git clone https://github.com/hecko/env_monitoring.git
cd env_monitoring/server
npm install
node ./app.js
```

== Raspberry PI client

=== Installation

```bash
cd /opt
git clone https://github.com/hecko/env_monitoring.git
```
