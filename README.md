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

### Cron
Cron should execute REST index every 60 sec. Simple example: ```*	*	*	*	*	lynx -dump http://rest.encrypted.pl >/dev/null 2>&1```

# Documentation

## Core Module
This module covers listing of keys and server status

### List keys and status
- authentication: NO
- request: ```GET http://rest.encrypted.pl```
- response JSON:
```
{
  "app": "REST Api @Phalcon 2.0.7",
  "sha1(password)": "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8",
  "keys": [
    {
      "key": "d6217d4c46c4131fd8924435cbf6b4490aa66e37",
      "lifetime": 200
    },
    {
      "key": "51c0011feb08373844ec26093ab04401253e764e",
      "lifetime": 86
    }
  ],
  "passwords": [
    {
      "sha1(sha1(password) + key)": "f6bcb6f9b5128631826d9899efa1afdf563d3e58",
      "lifetime": 200
    },
    {
      "sha1(sha1(password) + key)": "113f936ba0622a3e7735acbbbf04f8db9e91b82e",
      "lifetime": 86
    }
  ]
}
```

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
- authentication: YES TWO STEP
- request: ```DELETE http://rest.encrypted.pl/users/1```
- request JSON:
```
{
    "old_password": "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"
}
```
- response: ```HTTP 204```

## Messages Module

### Create new message
- authentication: YES
- request: ```POST http://rest.encrypted.pl/messages```
- request JSON:
```
{
    "id_receiver": "1",
    "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus mollis faucibus. Etiam eget semper urna, eu finibus urna. Nulla nec erat id sapien scelerisque malesuada.",
    "type": "0"
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
        "title": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus mollis faucibus. Etiam eget semper urna, eu finibus urna. Nulla nec erat id sapien scelerisque malesuada.",
        "type": "0"
    },
    {
        "id": "2",
        "time": "1440533028",
        "title": "message_encoded_with_users_public_key",
        "type": "1"
    }
]
```

### List latest messages (inbox)
- authentication: YES
- request: ```GET http://rest.encrypted.pl/messages```
- optional request data: ```?offset=0&limit=1```
- response JSON:
```
[
  {
    "id": "2",
    "id_sender": "1",
    "time": "1440533028",
    "title": "Pellentesque sit amet ligula vitae justo dictum pretium. Sed sed gravida enim. Maecenas ut dignissim mi. In vitae felis a urna accumsan accumsan.",
    "type": "0"
  }
]
```