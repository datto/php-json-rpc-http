# Simple Server

This example creates a simple API, where the client queries a remote server
over HTTP(S) without authentication.


## Run the example

First, start the remote server:
```bash
cd examples/simple
php -S localhost:8080 'server.php'
```

Then run the example:
```bash
php client.php
```

## Extend the example

You can start by looking in the "src" directory. Then replace it all with your
project code.
