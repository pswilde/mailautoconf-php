#!/usr/bin/env bash
podman run --name mailautoconf \
  --rm \
  -p "8010:80" \
  -v ./config:/var/www/html/config \
  pswilde/mailautoconf
