#!/usr/bin/env bash
podman run --name mailautoconf-test \
  --rm \
  -p "8010:80" \
  -v ./src:/var/www/html/ \
  -v ./config:/var/www/html/config \
  -v ./test-entry.sh:/test-entry.sh \
  -v ./entrypoint.sh:/entrypoint.sh \
  --entrypoint "/bin/bash" \
  php:7.4-apache \
  /test-entry.sh
