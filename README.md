# phalcon-rest
This is basic REST Api written with Phalcon PHP. Current REST Api is released under ```http://rest.encrypted.pl/```

# Information

### Basic Auth
For some requests you need Basic Auth. Basic Auth test data are: ```login: admin``` and password ```sha1(sha1(password) . date('Y-m-d'))```. Current sha1 hashes can be found at main router: ```http://rest.encrypted.pl```

# Documentation

## Users Module
This module covers REST methods for creating, updating, editing and listing Users.

### GET Users list
- auth: *NO*
- request: ```http://rest.encrypted.pl/auth```
- response JSON:
```
[
  {
    "id": "1",
    "login": "JohnDoe"
  },
  {
    "id": "2",
    "login": "Foo Bar"
  },
  {
    "id": "3",
    "login": "admin"
  }
]
```
