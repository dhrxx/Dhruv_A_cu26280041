#!/bin/bash
# Configure and verify Apache on Kali Linux.
sudo apt update
sudo apt install -y apache2 curl
sudo systemctl enable --now apache2
sudo systemctl status apache2 --no-pager
curl -I http://localhost
# Open http://localhost in a browser. The default Apache page should load.
