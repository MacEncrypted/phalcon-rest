# phalcon-rest
Basic REST Api written with Phalcon PHP.

# Tests
Current REST Api is released under ```http://rest.encrypted.pl/```

### GET Messages
Messages sent from one user to another
- request: ```http://rest.encrypted.pl/messages/1/2```
- response JSON:
```
[
    {
        "id": "123",
        "time": "1438290158",
        "title": "Old good Lorem Ipsum"
    }
]
```

### GET Conversations
Get both sent and received messages from one user to another 
- request: ```http://rest.encrypted.pl/conversations/1/2```
- response JSON:
```
[
    {
        "id": "122",
        "time": "1430222755",
        "title": "Que?"
    },
    {
        "id": "123",
        "time": "1438290158",
        "title": "Old good Lorem Ipsum"
    }
]
```

### POST Message
Send message from one user to another
- request: ```http://rest.encrypted.pl/messages```
- request JSON:
```
{
  "id_sender": 1,
  "id_receiver": 2,
  "content": "Old good Lorem Ipsum"
}
```
- response JSON:
```
{
    "status": "OK",
    "data": "123"
}
```

### GET Authorization status
Get user list. Only authorized users got access to users list.
- request: ```http://rest.encrypted.pl/auth``` with Basic Auth ```login: admin``` and password ```sha1(sha1(password) . date('Y-m-d'))```. Current sha1 hashes can be found at main router: ```http://rest.encrypted.pl```
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