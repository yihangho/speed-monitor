# Speed Monitor
A simple utility to keep track of your internet speed.

## Setting Up
This utility consists of two main parts - cron job and web server. It is possible to have one or two machines to handle these two parts.

The machine taking care of the cron job must be connected to the network that you want to monitor. This machine must have Ruby and [speedtest-cli](https://github.com/sivel/speedtest-cli) installed. Make sure that the script in `cron/cron.rb` is made executable, change the URL on [line 14](https://github.com/yihangho/speed-monitor/blob/master/cron/cron.rb#L14) to point to your web server. Also, change the absolute path metioned on [line 6](https://github.com/yihangho/speed-monitor/blob/master/cron/cron.rb#L6) to the absolute path of `speedtest-cli`, in order words, the result of `which speedtest-cli`. Finally, set up your crontab to execute `cron/cron.rb` at a desired interval.

The recommended web server is the usual LAMP server. Create a MySQL table as described in `www/schema.sql`. Also, make necessary changes to the following files:

1. `www/index.php`:
    - [line 9](https://github.com/yihangho/speed-monitor/blob/master/www/index.php#L9)
2. `www/commons/mysql.php`:
    - [line 2](https://github.com/yihangho/speed-monitor/blob/master/www/commons/mysql.php#L2)

## Configuration
### Secret Key
If your web server listens to the public network, it is advisable to set up a secret key, defined on [line 2](https://github.com/yihangho/speed-monitor/blob/master/www/commons/config.php#L2) of `www/commons/config.php` and [line 8](https://github.com/yihangho/speed-monitor/blob/master/cron/cron.rb#L10) of `cron/cron.rb`. The values in these 2 places must match.

### Definition of low speed
You can define what is meant by low speed on [line 3](https://github.com/yihangho/speed-monitor/blob/master/www/commons/config.php#L3) of `www/commons/config.php`. Any entry with download speed less than this value will be shown in red.

### Pagination
You can specify the number of results to show per page on [line 4](https://github.com/yihangho/speed-monitor/blob/master/www/commons/config.php#L4) of `www/commons/config.php`.

### Force HTTPS
If your web server supports both HTTP and HTTPS, but you wish to allow submission through HTTPS _only_, then you should change the value defined on [line 5](https://github.com/yihangho/speed-monitor/blob/master/www/commons/config.php#L5) of `www/commons/config.php` to `true`.
