# positive
Positive is a web-based stock control application that is built specifically for retailers of shoes. The application enables the retailer to keep track of both sales, stock, customer deposits and generate reports in different formats such as PDF, CSV either daily, weekly, or even monthly.

System Requirements
---------------------
Your system should be able to meet the following requirements for you to be able to install it:
*PHP 5.3+ (Very Important) PHP 5.3 is required since it introduced late static bindings in the case
of inheriting methods that have been declared as static. Most classes in the system extend from a
database abstraction class that has most of its methods that do non-class specific operations declared as static
*MySQL 5.0+
*An HTTP Server, preferably Apache 2.2+

Required Extensions
--------------------
Perl Compatible Regular Extensions(PCRE) must be installed on your server. They are used for data validation purposes in the system

Installation
---------------
Download and extract the application files and folders into a folder located within your web server document root.
For example, your can create a folder named "positive" in your web server document root.

Create the One-Square-Foot Database
-----------------------------------
Create the application's database by either using the MySQL Console (a command-line-like utility for operating on MySQL), or a MySQL administration application like phpmyadmin.

Edit INIT And Database Class Files
-----------------------------------
The credentials you used in creating the application's database are used in determining site-wide application constants that are loaded with an "init" file during application launch. For example, say you created the the database with a name of "database", a username of "username", and a password of "password", open the class.Database.php file located in the "lib/" directory of your application and edit the database connection constants to match your credentials accordingly.

Next you need to edit the constant that defines the root of the application. It's located in the "init.php" file in the "lib/"
directory. The line typically looks like:

##### defined('SITE_ROOT') ? NULL : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'app_pos');

The above line defines the root of your application. The portion **"app_pos"** in the above line is the name of the folder located in your web server document root into which you extracted the application's files and folders. Rename it to match the name you gave to that folder. If you have defined a virtual host then you need to edit the above line and remove the folder name.

Edit Database File
--------------------
Edit the database file that contains structure of the application's database. Open the **"app_pos.sql"** file using a text editing tool like notepad, then edit the database name to correspond to the name you assigned to the database created in the
previous step. The line that contains information about the database typically looks like:

###### 

--
-- Database: `app_pos`
--
CREATE DATABASE IF NOT EXISTS `app_pos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `app_pos`;

-- -----

In the above line, replace every instance of **"app_pos"** with the name you gave your database. Save the file, then import it using either the MySQL console or phpmyadmin to setup the database tables for the application.

You are now good to go. Go to the the browser and fire up the application from the base URL of your web server. E.g, say you 
are running the application on localhost, and extracted it to a folder named "one-sqaure-foot", go to http://localhost/app_pos and get started using the application by logging in as an administrator using a username of "admin" and password of "pass". You can now start using the application by setting up properties, rooms, tenants, taking payments, and producing reports.




