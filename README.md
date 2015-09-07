# Phalcon REST Api 

### phalcon-rest
This is basic REST Api written with Phalcon PHP. Current REST Api is released under  [http://rest.encrypted.pl/](http://rest.encrypted.pl/)

### License 
MIT is a short, permissive software license. Basically, you can do whatever you want as long as you include the original copyright to *Encrypted.pl* and license notice in any copy of the software/source.

You can
- Commercial Use
- Modify
- Distribute
- Sublicense
- Private Use


You Cannot
- Hold liable

You Must
- Include Copyright
- Include License

# Information

### Basic Auth
For some requests you need Basic Auth. Basic Auth test data are: ```login: admin``` and password ```sha1(sha1(password) . round_key)```. Current sha1 hashes  and round_keys can be found at main router: ```http://rest.encrypted.pl```

###
Cron should execute REST index every 60 sec.

# Documentation

## Users Module
This module covers REST methods for creating, updating, editing and listing Users.

### Create new user
- authentication: NO
- request: ```POST http://rest.encrypted.pl/users```
- request JSON:
```
{
    "login": "admin",
    "password": "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8",
    "pubkey": "public_key_for_your_messages_encoding"
}
```
- response JSON:
```
{
    "status": "OK",
    "data": "1"
}
```

### List all users
- authentication: NO
- request: ```GET http://rest.encrypted.pl/users```
- response JSON:
```
[
    {
        "id": "1",
        "login": "admin"
    },
    {
        "id": "2",
        "login": "John Doe"
    },
    {
        "id": "3",
        "login": "Foo Bar"
    }
]
```

### List one user
- authentication: NO
- request: ```GET http://rest.encrypted.pl/users/1```
- response JSON:
```
{
    "id": "1",
    "login": "admin"
}
```

### Update user data
- authentication: YES TWO STEP
- request: ```PUT http://rest.encrypted.pl/users/1```
- request JSON:
```
{
    "login": "admin_new",
    "password": "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8",
    "pubkey": "public_key_for_your_messages_encoding",
    "old_password": "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"
}
```
- response JSON:
```
{
    "status": "OK",
    "data": "1"
}
```
- note: since login is unique you MUST change it when updating User, BUG: https://github.com/phalcon/cphalcon/issues/1527

### Delete user
- authentication: YES
- request: ```DELETE http://rest.encrypted.pl/users/1```
- response: ```HTTP 204```

## Messages Module

### Create new message
- authentication: YES
- request: ```POST http://rest.encrypted.pl/messages```
- request JSON:
```
{
    "id_receiver": "1",
    "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus mollis faucibus. Etiam eget semper urna, eu finibus urna. Nulla nec erat id sapien scelerisque malesuada."
}
```
- response JSON:
```
{
    "status": "OK",
    "data": "1"
}
```

### List messages from one user to another
- authentication: YES
- request: ```GET http://rest.encrypted.pl/messages/3/1```
- optional request data: ```?offset=100&limit=500```
- response JSON:
```
[
    {
        "id": "1",
        "time": "1440532798",
        "title": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus mollis faucibus. Etiam eget semper urna, eu finibus urna. Nulla nec erat id sapien scelerisque malesuada."
    },
    {
        "id": "2",
        "time": "1440533028",
        "title": "Pellentesque sit amet ligula vitae justo dictum pretium. Sed sed gravida enim. Maecenas ut dignissim mi. In vitae felis a urna accumsan accumsan."
    }
]
```

### List latest messages (inbox)
- authentication: YES
- request: ```GET http://rest.encrypted.pl/messages/inbox```
- optional request data: ```?offset=0&limit=1```
- response JSON:
```
[
  {
    "id": "2",
    "time": "1440533028",
    "title": "Pellentesque sit amet ligula vitae justo dictum pretium. Sed sed gravida enim. Maecenas ut dignissim mi. In vitae felis a urna accumsan accumsan."
  }
]
```