# What's this?

This is a collection of REST APIs used by my [Unreal Engine 4: Online Login/Registration](https://www.youtube.com/playlist?list=PLaVz4AmlosnFixCPTJNsLxQpiGSijIOXy) YouTube tutorial series. 

The following features are provided out of the box:
 - Player authentication/login.
 - Player registration with email activation.
 - Player logout.
 - Simple contact information like first and last name is collected during registration.
 - Account password recovery using email.
 - Authentication sessions.
  
Check out the [YouTube playlist](https://www.youtube.com/playlist?list=PLaVz4AmlosnFixCPTJNsLxQpiGSijIOXy). Please Like, Subscribe and Share :) 

# Environment Setup
1. Install XAMPP with PHP 7.0 https://www.apachefriends.org/download.html
2. Add PHP path to your Windows PATH environment variable
3. Install HeidiSQL https://www.heidisql.com/
4. Download Phalcon and put in PHP ext/ folder https://phalconphp.com/en/download/
5. Update php.ini, load php_phalcon.dll
6. Install composer https://getcomposer.org/
7. Setup/check Phalcon dev-tools is configured and working in project
8. Import SQL script
9. Apply DB migrations (if any available)
10. Setup Apache/Nginx Virtual Host.

# References
- [Using GUIDs with MySQL MariaDB](https://mariadb.com/kb/en/mariadb/guiduuid-performance/)
- [Storing IPs in MySQL MariaDB](https://dev.mysql.com/doc/refman/5.6/en/miscellaneous-functions.html#function_inet6-aton)
- [Configuring Apache for Phalcon](https://docs.phalconphp.com/en/latest/reference/apache.html)
- [Configuring Nginx for Phalcon](https://docs.phalconphp.com/en/latest/reference/nginx.html)
- [Configuring PhpStorm IDE stubs for Phalcon](https://phalconphp.com/en/download/stubs)
- [JsonMapper library](https://github.com/cweiske/jsonmapper)