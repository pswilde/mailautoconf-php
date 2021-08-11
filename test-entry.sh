#!/usr/bin/env bash
a2enmod rewrite
service apache2 stop
exec apache2-foreground
