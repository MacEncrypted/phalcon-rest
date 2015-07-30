# phalcon-rest
Basic REST Api written with Phalcon PHP.

# Tests
Current REST Api is released under ```http://rest.encrypted.pl/```

### GET Messages
Messages sent from one user to another
- request: ```http://rest.encrypted.pl/messages/1/2```
- response: JSON

### GET Conversations
Get both sent and received messages from one user to another 
- request: ```http://rest.encrypted.pl/conversations/1/2```
- response: JSON

### POST Message
Send message from one user to another
- request:
```
{
  "id_sender": 1,
  "id_receiver": 2,
  "content": "Old good Lorem Ipsum"
}
```
- response: JSON
```
{
    "status": "OK",
    "data": "123"
}
```