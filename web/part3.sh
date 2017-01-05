#!/bin/bash -ex

DIR=$(pwd)
lsb_release -a

echo "machine localhost login dummy password dummy" > ~/.netrc

# configure and restart apache
sudo cp /opt/brackets/bracketsserver/etc/apache.conf /etc/apache2/sites-enabled/
sudo service apache2 restart

# add users/group for judgedaemons 
sudo useradd -d /nonexistent -g nogroup -s /bin/false brackets-run
sudo useradd -d /nonexistent -g nogroup -s /bin/false brackets-run-0
sudo useradd -d /nonexistent -g nogroup -s /bin/false brackets-run-1
sudo groupadd brackets-run

# configure judgehost
cd /opt/brackets/judgehost/
sudo cp /opt/brackets/judgehost/etc/sudoers-domjudge /etc/sudoers.d/
sudo chmod 400 /etc/sudoers.d/sudoers-brackets
sudo bin/create_cgroups

# build chroot (modify amd64 with your architecture (e.g. i386))
cd ${DIR}/misc-tools
sudo ./dj_make_chroot -a amd64





