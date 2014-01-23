#!/bin/bash

mysqldump -d -u root --skip-add-drop-table -p marcel.env > server/install/mysql.schema
