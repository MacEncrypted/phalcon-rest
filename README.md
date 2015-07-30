# phalcon-rest
Basic REST Api written with Phalcon PHP.

# Tests
Current REST Api is released under ```http://rest.encrypted.pl/```

* GET messages sent from one user to another
request: ```http://rest.encrypted.pl/messages/1/2```
response: ```[
    {
        "id": "1",
        "time": "1000",
        "title": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vitae turpis placerat, vestibulum nisl et, vehicula urna. In ipsum eros, rutrum non nulla ut, malesuada mattis nunc. Vivamus aliquam, nulla et tincidunt commodo, massa lectus faucibus felis, sed bibendum quam risus ac lacus."
    }
]```