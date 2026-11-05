#!/bin/bash
# Install common PHP extensions on Kali Linux/Debian and verify them.
sudo apt update
sudo apt install -y php-mysql php-mbstring php-curl
php --ini
php -m | grep -Ei 'mysqli|pdo_mysql|mbstring|curl'
sudo systemctl restart apache2
