# Prequisites

This example shows how you might create a web API, where clients communicate
with a remote API server over HTTP.

In order to run the example, you'll need support for the URL "http://json-rpc-http/".
If you're using Linux in your development environment, you can set this up
by editing two files:

## File 1:

`sudo gedit /etc/hosts`
```
127.0.0.1   localhost json-rpc-http
```

## File 2:

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

`sudo ln -s /etc/apache2/sites-available/api.conf /etc/apache2/sites-enabled/api.conf`

`sudo service apache2 restart`
