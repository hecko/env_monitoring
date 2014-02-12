# Environmental Monitoring and control using Arduino and Raspberry PI

Three parts
- client part (run on raspberry gathering unit
- server part (run on the cloud server)
- arduino firmware (run on arduino or other avr mcu)


## Server 

We have two versions of the server - Node.js + MongoDB based server and PHP + MySQL based server.

### Node.js + MongoDB

#### Prerequisities

* mongodb database (mongod running)
* node package manager (npm)

#### Running

```bash
sudo npm install n -g
sudo n stable
git clone https://github.com/hecko/env_monitoring.git
cd env_monitoring/nodejs_server
npm install
node ./app.js
```

### PHP + MySQL server

See directory php_server.

#### Prerequisities

* apply MySQL schema to a MySQL database
* edit config.php

## Raspberry PI client

Application installed on Raspberry PI gathering data from Arduino and posting the data to the server.

### Installation

```bash
cd /opt
git clone https://github.com/hecko/env_monitoring.git
cp env_monitoring/rpi_client/env.cron /etc/cron.d/env
```
