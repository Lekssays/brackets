#!/bin/bash -ex

DIR=$(pwd)
lsb_release -a

# install composer per the composer docs, see:
# https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md
EXPECTED_SIGNATURE=$(wget https://composer.github.io/installer.sig -O - -q)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" = "$ACTUAL_SIGNATURE" ]; then
    php composer-setup.php --filename=composer
    rm composer-setup.php
else
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi

# install all php dependencies
./composer install --no-dev

# downgrade java version outside of chroot since this didn't work
sudo apt-get remove -y openjdk-8-jdk openjdk-8-jre openjdk-8-jre-headless oracle-java7-installer oracle-java8-installer

# configure, make and install (but skip documentation)
make configure
./configure --disable-doc-build --with-baseurl='http://localhost/brackets/'
make bracketsserver judgehost
sudo make install-bracketsserver install-judgehost

# setup database and add special user
cd /opt/brackets/bracketsserver
## Modify root and toor with your mysql credentials
sudo bin/dj_setup_database -u root -p toor install




