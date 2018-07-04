# Equipments rent
#### Version 1.0b
#### Author: Vitalii Minenko

A simple application  which will help us to check available equipment for rent.

##### Haw we can start app.
For start application you must have min PHP 5.4.0
* Start the application from the public_html folder
* When you are in a public_html folder , type the command php -S localhost:3000 in the command line
* You can check application in your browser im address localhost:3000
* When you set all configurations for connection to database type next commands.
```
php artisan migrate
``` 
```
php artisan db:seed --class=EqupmentTableSeeder
``` 
* Now application is ready you can use it with API interfaces:

##### Method of HTTP Request.

* POST

##### Headers of HTTP Request.
* Content-Type : application/json

##### Api commands and example of answers.

If we wont to check in which days our equipment is on rent.

* Example Url for check.
```
http://localhost:8000/api/getRentPeriod/
```
##### Api can accept the following parameters.
* date - Date with which we begin the search in format `2018-01-01`.
* N - Days in which we are looking for rent fromat `4`.

```
	{
    	"date":"2018-06-29",
    	"days":"3"
    }
```


If we wont to set new rent period or add new Equipment with rent period.
* Example Url for set new Rent dates for equipment. If equipment not exist we add id like new into db. 
Put the rent period in the first closest suitable for the parameters of the day of the week. If it will not available api will 
return `Day is not empty. Try to choose another day.`
```
http://localhost:8000/api/addRentPeriod/
```
##### Api can accept the following parameters.
* equpment - Name od equipment
* week_day - Nmae of week day in format Short english week days `Thu`
* duration - Period of rent in day format `H:i`.

```
{  
   "equipment":"Excavator 011",
   "week_day":"Tue",
   "duration":{  
      "start_duration":"9:00",
      "end_time":"14:00"
   }
}
```
* Example of answers .
```
{
status: "ok",
message: "Day is not empty. Try to choose another day."
}
```
Or
```
{
status: "ok",
message: "The date was add successfully."
}

```

