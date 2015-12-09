# Simple Server

This example creates a simple API, where the client queries a remote server
over HTTP(S) without authentication.

Take a look at the "src" directory: This is the code that you would write
for your own project!

## Preparation

Before you can run this example, you'll need to add support for HTTP requests
on your development environment.

First, make sure that you've installed Apache and PHP in your development
environment:

```bash
apt-get install apache2 php5
```

Next, add support for the address "http://json-rpc-http":

`sudo gedit /etc/apache2/sites-available/json-rpc-http.conf`
```
 <VirtualHost *:80>
	ServerName json-rpc-http
	ServerAdmin webmaster@localhost

	# CHANGE THIS PATH #
	DocumentRoot /var/www/datto/json-rpc-http/examples

	# CHANGE THIS PATH #
	<Directory /var/www/datto/json-rpc-http/examples>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Require all granted
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Now activate your changes like this:

```bash
sudo a2ensite json-rpc-http
sudo service apache2 reload
```

Finally, make the address resolve on your development environment by editing
your "hosts" file:

`sudo gedit /etc/hosts`
```
127.0.0.1   localhost json-rpc-http
```

Now you're ready to run the examples!

## Run the example

You can run the example like this:
```bash
php client.php
```
