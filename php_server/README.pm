= Install =

- create mysql database iboredco_weather
- create user iboredco_mac
- allow access for the user to the database
- change the details in config.php
- create database structure (e.g. create tebles):
  mysql -h localhost -p -u iboredco_mac iboredco_weather < mysql_struct.sql
- load index.php in browser

= Notes =

== Dumping MySQL database structure ==

mysqldump -d -h localhost -p -u iboredco_mac iboredco_weather > mysql_struct.sql
