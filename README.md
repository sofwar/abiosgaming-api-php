# AbiosGaming API PHP

A simple object-oriented approach to data in the AbiosGaming API.

For more information about the AbiosGaming API, refer to the [official API documentation](https://docs.abiosgaming.com/v2/).

## Requirements

* PHP 5.6 or greater
* OAuth Client ID and Secret from Abios Gaming. Contact Abios Gaming support to obtain a key pair.

## Installation

```
$ composer require sofwar/abiosgaming-api
```

## Usage

```php
//Create API
$api = new SofWar\AbiosGaming\API($clientId, $clientSecret);
```

## License

Copyright 2017 SofWar, Inc.

Free for you to use under MIT.