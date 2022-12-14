# PHP Lucca API

A basic and stand-alone PHP client interracting with the Lucca RH API V3 services.
For more information about the API please see the [documentation](https://developers.lucca.fr/docs/lucca-legacyapi/ZG9jOjM3OTk0NDk5-getting-started).

## Installation

The recommended way to install Lucca API PHP client is through this Github repository:

```json
    {
        "requires": {
            "alpifra/lucca-php": "dev-master",
            ...
        }
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/Alpifra/lucca-php.git"
            }
        ],
    }
```

## Usage

#### Instanciate client and request leaves

```php
<?php

$ownerId = 23;
$date = ['between', '2021-01-01', '2021-01-31'];

$client = new Alpifra\LuccaPHP\TimmiAbsences('***API_KEY***');
$client->setFields('id', 'name', 'url');
$client->list($ownerId, $date);
```

## Contribution

This package only implement the Lucca API service that I need ([Timmi Absences](https://developers.lucca.fr/docs/lucca-legacyapi/2713ebbef0217-timmi-absences-api)), but you're welcome to contribute to this repository with you own implementation by sending me a PR. Happy coding !