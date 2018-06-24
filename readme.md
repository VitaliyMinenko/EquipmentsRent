# Api for get information from GitHub repositories
#### Version 1.0b
#### Author: Vitalii Minenko

A simple application that provides an interface for retrieving statistics on GitHub repositories

##### Url for request.

```
http://www.test.loc
```

##### Params for request.

```
{
	"first_repository":"VitaliyMinenko/app",
	"second_repository":"VitaliyMinenko/statx"
}
```

##### Method of HTTP Request.

* POST

##### Headers of HTTP Request.
* Content-Type : application/json
* Accept       : application/json

##### Example of answer.
```$xslt
{
    "status": "ok",
    "VitaliyMinenko/app": {
        "VitaliyMinenko/app": {
            "Number of forks": 0,
            "Number of stars": 0,
            "Number of watchers": 0,
            "Date of the latest release": "Date is undefined",
            "Pull requests": {
                "open": 0,
                "close": 0
            }
        }
    },
    "VitaliyMinenko/statx": {
        "VitaliyMinenko/statx": {
            "Number of forks": 0,
            "Number of stars": 0,
            "Number of watchers": 0,
            "Date of the latest release": "Date is undefined",
            "Pull requests": {
                "open": 0,
                "close": 0
            }
        }
    }
}
```