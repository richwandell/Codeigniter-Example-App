# This project
This project is an example of using Codeigniter with a full page cache and dynamic content.
 The different forms will effect the data that is displayed on other pages. This project demonstrates how 
 both browser cache and an object cache (redis) can be leveraged to speed up even the most dynamic data driven
 php applications without the need for a reverse proxy cache like varnish nginx or squid which can be overkill in 
 certain situations. 

# Things to pay attention to
This application shows how page cache can be used with dynamic content. 
Each Controller has pages that are cached for up to 4 hours.
When forms are submitted the page is then cleared from cache along with any other pages
that would be effected by the new data. For example... if I were to add a new part to a car
the car list page as well as the car parts list page and the parts list pages would all be 
removed from cache because the data on that page should change.

Dynamic messages and csrf tokens are loaded through ajax so that flash messages can still be used
and forms can still be submitted see all.js file in /static/ folder for details and getCsrfToken function
in the car controller. 



## Controllers in application/controllers

* Car
    * Add function for adding a new Car
    * List function for listing the Cars
    * Delete function for deleting a Car
    * Passenger List function for showing the Passengers in the car
    
## Doctrine Entities in application/models/Entities
* Car
    * Cars have a ID , Make, Model, Year, Passengers, and Parts
* Passenger
    * Passengers ride in cars, have a first name and a last name
    
* Part
    * Cars have parts. A part has a name and a price.
    

## Installation
* Change the database config in config/database.php
* Create a database with the correct name on your server
* Run composer install
* Update your database using doctrine command line
```SH
php vendor/bin/doctrine orm:schema-tool:create
```
* Update the redis config in config/config.php