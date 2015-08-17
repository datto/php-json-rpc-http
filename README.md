# JSON-RPC for PHP

## Features

* Fully compliant with the [JSON-RPC 2.0 specifications](http://www.jsonrpc.org/specification) (with 100% unit-test coverage)
* Flexible: you can choose your own system for interpreting the JSON-RPC method strings
* Dependable: works even when CURL is not installed
* Minimalistic (just two tiny files)
* Ready to use, with working examples

## Requirements

* PHP >= 5.3

## License

This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html)

## Examples

### Client

```php
$client = new Client('http://api.example.com');

$client->query(1, 'add', array(1, 2));

$reply = $client->send();
```

### Server

```php
$server = new Server(new Api());

$server->reply();
```

*See the "examples" folder for ready-to-use examples.*

## Installation

If you're using [Composer](https://getcomposer.org/), you can use this package
([datto/json-rpc-http](https://packagist.org/packages/datto/json-rpc-http))
by inserting a line into the "require" section of your "composer.json" file:
```
        "datto/json-rpc-http": "~3.0"
```

## Getting started

1. Try the examples! Follow the README file in the "examples" directory to
set up a development web environment. Run the examples from the project directory
like this:
	```
	php examples/client.php
	```

2. Once your example is working, replace the
[example "src" code](https://github.com/datto/php-json-rpc-http/tree/master/examples/src)
with your own code.

3. Use your new API in a project.


## Author

[Spencer Mortensen](http://spencermortensen.com/contact/)
