::
:: Start the internal webserver. 
::
:: In the php.ini set variables_order = "EGPCS" to pick up environment variables
call _environment.cmd
cd  web_app
php -S localhost:8080
