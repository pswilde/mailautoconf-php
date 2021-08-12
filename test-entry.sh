#!/usr/bin/env bash
a2enmod rewrite
service apache2 stop
exec bash /entrypoint.sh
