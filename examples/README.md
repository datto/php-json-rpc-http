# Authenticated Server

This example creates an API where the client queries a remote server over HTTP(S).


## Run the examples

Start in the "examples" directory:
```bash
cd examples
```

Be sure to include the Composer dependencies:
```bash
composer install
```

Start the remote server (and leave it running):
```bash
php -S localhost:8080
```

Now run the examples:
```bash
php clientSimple.php
php clientAuthenticatedValid.php
php clientAuthenticatedInvalid.php
```


## Extend the examples

You can start by looking in the "src" directory. Then replace it all with your
project code.
