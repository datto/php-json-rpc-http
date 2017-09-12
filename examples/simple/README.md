# Simple Server

This example creates a simple API, where the client queries a remote server
over HTTP(S) without authentication.


## Run the example

First, install the Composer dependencies. You should run this command inside
the "simple" directory:
```bash
composer install
```

Now start the remote server:
```bash
php -S localhost:8080 'bin/server.php'
```

And run the example!

```bash
php client.php
```

## Extend the example

You can start by looking in the "src" directory. Then replace it all with your
project code.
