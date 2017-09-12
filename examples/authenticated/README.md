# Authenticated Server

This example creates an authenticated API, where the client queries a remote server
over HTTP(S) using [basic access authentication](https://en.wikipedia.org/wiki/Basic_access_authentication).


## Run the examples

First, install the Composer dependencies. You should run this command inside
the "authenticated" directory:
```bash
composer install
```

Now start the remote server:
```bash
php -S localhost:8080 'bin/server.php'
```

And run the examples!

```bash
php clientInvalidCredentials.php
php clientValidCredentials.php
```

## Extend the example

You can start by looking in the "src" directory. Then replace it all with your
project code.
