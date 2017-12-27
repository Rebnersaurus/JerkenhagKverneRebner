# JerkenhagKverneRebner


# Table of contents
- Project description
- Prerequisites
- Installation
- Running the application
- Built with
- Contributing
- Authors
- License
- Acknowledgments

# Project Description
The goal of the project is to create a dynamic calendar-based application that will assist the user in managing her meetings as well as the travel means in between them in a given city or region. The application should allow for a customizable way of getting the directions that the user needs based on her preferences when it comes to mode of transportation as well as the ecologic impact. Consideration should also be taken to the day, for example if there’s a strike or the weather can have an impact the app will notify the user and route accordingly.

# Prerequisites
- XAMPP. Link: https://www.apachefriends.org/download.html
- MAMP. Link: https://www.mamp.info/en/downloads/
      Sidenote: On this page, both MAMP and MAMP PRO are available. For testing Travlendar+ the paid version MAMP PRO is not required. 
- Any up to date web browser

# Installation
- Copy the files from the repository and download.
- Download either XAMPP (PC) or MAMP (mac)

XAMPP instructions
When XAMPP has been installed on the users computer and the Travlendar+ files has been acquired from the project group and added to the htdocs the application can be tested. In XAMPP select “Start” in the “General” tab. Once the status is active and the IP address is shown navigate to the “Services” tab and start to initialize the Apache and MySQL server. From here, go through the steps of general instructions above to reach Travlendar+.

Once the user has arrived at this page they can create a new user profile and log in to the application. From here the different functionalities implemented in the prototype can be tested by following the test cases we have detailed. To reach the back-end database go to “localhost/phpmyadmin”. Here the user can view the tables of the database for Travlendar+ as well as their associated SQL triggers.

MAMP instructions
Once MAMP is running on the users computer, the user should select “Start Servers” in order to initialize the apache server and the MySQL server. Then select “Open Webstart page”, this will bring the user to the MAMP webstart page where the user can navigate both to the user interface of Travlendar+ as well as the MySQL database in the back-end. To get to the application select “My Website” which will take the user to the localhost page where Travlendar+ is located. Once the user has arrived at this page they can create a new user profile and log in to the application. From here the different functionalities implemented in the prototype can be tested by following the test cases we have detailed. 

To get to the SQL database in order to view the back-end information stored in the tables, select “phpMyAdmin”. Once within phpMyAdmin the user can open the “travlendar” tree and view the tables and their contents.

# Running the application
- Run XAMPP (PC) or MAMP (mac)
- Once you have started the apache server and the MySQL server type, in your web browser, “localhost/Travlendar” to get to the web application
- In order to get the application to interact with the database go to “localhost/phpmyadmin” and create a new database from the file in the project


# Built with

- HTML
- Javascript
- CSS
- MySQL

# Authors

- Axel Rebner
- Joakim Jerkenhag
- Caroline C. S. Kverne

# License

Copyright © 2017, Jerkenhag, Kverne, Rebner All rights reserved 

# Acknowledgements

Thank you Elisabetta di Nitto for good and thoughtfull responses to our questions. Also thank you to the class of Software Engineering for good lessons and teaching activities. We have enjoyed working on the project, and think the idea has potential for further development. 


