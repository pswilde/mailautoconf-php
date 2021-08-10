#!/usr/bin/env bash
cp /var/www/html/sample-config/* /var/www/html/config/
exec apache2-foreground
