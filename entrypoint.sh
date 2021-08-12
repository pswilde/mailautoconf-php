#!/usr/bin/env bash
echo Removing old sample files…
rm /var/www/html/config/*.sample.ini

function write_file() {
  while read line;
  do
    first_char=${line:0:1}

    if [[ $first_char != ";" ]]; then
      line="; "$line
    fi
    echo $line >> $2
  done < $1
}

echo Setting up new sample config files…
def_conf="/var/www/html/default-config/config.default.ini"
new_conf="/var/www/html/config/config.sample.ini"
write_file $def_conf $new_conf

def_serv="/var/www/html/default-config/services.default.ini"
new_serv="/var/www/html/config/services.sample.ini"
write_file $def_serv $new_serv



echo Running HTTPD…
exec apache2-foreground
