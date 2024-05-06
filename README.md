### Setup the project
1. cd /var/www
2. git clone https://github.com/tsarukyana/CurrencyExchangeService.git
3. cp .env.example .env
4. modify .env and put correct database connection
- example
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=currency_exchange
DB_USERNAME=root
DB_PASSWORD=eeduawa5eeneeceezahxii0eish2Pha5
```
5. composer install
6. php artisan migrate
7. make sure there are correct permissions of folders storage and bootstrap/cache

```sh
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```
8. add below line inside `crontab -e`

```sh
 * * * * * php /var/www/CurrencyExchangeService/artisan schedule:run 1>> /dev/null 2>&1
```
9. Create Supervisor config file `/etc/supervisor/conf.d/exchange-rates.conf`

```
[program:exchange-rates-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/CurrencyExchangeService/artisan queue:work --timeout=3600 --sleep=3 --tries=5 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/CurrencyExchangeService/storage/logs/queue.log
stopwaitsecs=3600
```
10. activate above configuration
```sh
sudo supervisorctl reread
sudo supervisorctl update
```
11. Run the project via `php artisan serve`
12. Manually we can fetch exchange rates via below command
```sh
php artisan app:fetch-exchange-rates
```
13. Open url http://127.0.0.1:8000/api/rates or filtering by exchange date http://127.0.0.1:8000/api/rates?date=2024-05-07 
14. For analyze the code you can run `./vendor/bin/phpstan analyse` command. TODO: need to create tests for whole functionality.




