#!/usr/bin/env bash
podman run --name davdiscover \
  --rm \
  -p "8010:80" \
  -v ./config:/var/www/html/config \
  pswilde/davdiscover
