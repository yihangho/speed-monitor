# Speed Monitor
A simple utility to keep track of your internet speed.

## Setting Up
This utility consists of two main parts - cron job and web server. It is possible to have one or two machines to handle these two parts.

The machine taking care of the cron job must be connected to the network that you want to monitor. This machine must have Ruby and [speedtest-cli](https://github.com/sivel/speedtest-cli) installed. Make sure that the script in `cron/process.rb` is made executable, change the URL on [line 11](https://github.com/yihangho/speed-monitor/blob/master/cron/process.rb#L11) to point to your web server. Also, change the absolute paths metioned on [line 2](https://github.com/yihangho/speed-monitor/blob/master/cron/cron.sh#L2) of `cron/cron.sh` to the absolute paths of `speedtest-cli` and `cron/process.rb`. Finally, set up your crontab to execute `cron/cron.sh` at a desired interval.

The recommended web server is the usual LAMP server. Create a MySQL table as described in `www/schema.sql`. Also, make necessary changes to the following files:

1. `www/index.php`:
    - [line 2](https://github.com/yihangho/speed-monitor/blob/master/www/index.php#L2)
    - [line 9](https://github.com/yihangho/speed-monitor/blob/master/www/index.php#L9)
2. `www/api/submit.php`:
    - [line 16](https://github.com/yihangho/speed-monitor/blob/master/www/api/submit.php#L16)