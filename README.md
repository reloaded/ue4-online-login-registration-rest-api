# What's this?

This is a collection of REST APIs used by my 
[Unreal Engine 4: Online Login/Registration](https://www.youtube.com/playlist?list=PLaVz4AmlosnFixCPTJNsLxQpiGSijIOXy) 
YouTube tutorial series. 

The following features are provided out of the box:
 - Player authentication/login.
 - Player registration with email activation.
 - Player logout.
 - Simple contact information like first and last name is collected during registration.
 - Account password recovery using email.
 - Authentication sessions.
  
Check out the [YouTube playlist](https://www.youtube.com/playlist?list=PLaVz4AmlosnFixCPTJNsLxQpiGSijIOXy). Please Like,
Subscribe and Share :) 

# Environment Setup
1. Install XAMPP with PHP 7.0 https://www.apachefriends.org/download.html
1. Add PHP path to your Windows PATH environment variable
1. Install HeidiSQL https://www.heidisql.com/
1. Download Phalcon and put in PHP ext/ folder https://phalconphp.com/en/download/
1. Update php.ini, load php_phalcon.dll
1. Install composer https://getcomposer.org/
1. Clone this repo or download and extract it.
1. Setup Apache/Nginx Virtual Host.
1. Navigate to the root of this repo where the `composer.lock` file exists and execute `composer install` to install PHP
dependency libraries.
1. Setup/check Phalcon dev-tools is configured and working in project.
1. Import SQL script
1. Apply DB migrations (if any available)

NOTE: There might be issues with generating GUID/UUID in PHP. See more under the Requirements section of the PHP 
composer package [ramsey/uuid](https://github.com/ramsey/uuid)

# GUID/UUID as Primary Keys in the DB
This project uses GUID as primary keys (and columns that reference those PRIMARY KEYS) for all critical tables. 
Some examples of tables that use GUID as primary keys are `players`, `player_account_recovery` and `player_sessions`.

The reasons behind using GUID instead of integer columns that `auto_increment` are as follows:

1. Replication between sharded/geo-sharded databases are less prone to duplicate PRIMARY KEY values that would come 
with using basic integer values.
2. Security! If integer values were used to identify a player in the system then someone can easily type in 
(or build a script) that sends requests to the API using random integers until it finds an integer that appears to have 
a player (or whatever object in the DB the integer represents) associated to it. Even with the use of scripts it's 
impossible to try a brute force method against a GUID value using this method.

Because GUID is 32 characters in length the GUID is stored in the database tables as a Binary(16) value. This saves
database file and index space and provides other performance benefits compared to storing the GUID as a regular char(32)
or varchar(32) column.

To read a Binary GUID column in the DB table you need to pass the column value(s) into the MySQL HEX() function. For 
example `select hex(id) from players;`. Using this method you can easily read the GUID values as strings in phpMyAdmin,
MySQL Work Bench, Heidi SQL or any other DB management application you'd like.

Joining, filtering, etc on binary GUID values work the same as any other column. Just join the binary 
GUID columns WITHOUT calling the `HEX()` function because it's unnecessary to convert the binary value to string.

Note: The process of converting these GUIDs between Binary and Hex strings in the PHP REST API code is handled in the 
Phalcon models in the `beforeSave()`, `afterSave()` and `afterFetch()` Phalcon model methods. 
[Read more about these methods here](https://docs.phalconphp.com/en/latest/reference/models.html#initializing-preparing-fetched-records)

# Troubleshooting
- If you have an issue with generating UUID/GUID values in the PHP REST API see the Requirements section of the 
PHP composer package [ramsey/uuid](https://github.com/ramsey/uuid) being used to generate/parse GUID/UUID.

# References
- [Using GUIDs with MySQL MariaDB](https://mariadb.com/kb/en/mariadb/guiduuid-performance/)
- [Storing IPs in MySQL MariaDB](https://dev.mysql.com/doc/refman/5.6/en/miscellaneous-functions.html#function_inet6-aton)
- [Configuring Apache for Phalcon](https://docs.phalconphp.com/en/latest/reference/apache.html)
- [Configuring Nginx for Phalcon](https://docs.phalconphp.com/en/latest/reference/nginx.html)
- [Configuring PhpStorm IDE stubs for Phalcon](https://phalconphp.com/en/download/stubs)
- [JsonMapper library](https://github.com/cweiske/jsonmapper)