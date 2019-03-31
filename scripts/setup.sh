#!/bin/bash

####################################################################
#                                                                  #
#Script Name       : setup.sh                     				   #
#Description       : Configuration script that sets up			   # 
#					 all required packages and configs			   #
#Author            : Grzegorz Kawka-Osik                           #
#Version           : 1.0                                           #
#Creation date     : 06.01.2018                                    #
#                                                                  #
####################################################################

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

. "${DIR}/config.sh"

mkdir \
${stage} \
${stagePDF} \
${stageImage} \
${stageVideo} \
${output} \
${www} \
${wwwUploads} ${wwwUploads}/default ${wwwUploads}/schedule1 ${wwwUploads}/schedule2 \
${wwwUploads}/schedule3 ${wwwUploads}/schedule4 ${wwwUploads}/schedule5 \
${wwwUploads}/schedule6 ${wwwUploads}/schedule7

sudo apt-get update

#backend side
sudo apt-get install -y imagemagick libreoffice ffmpeg

#configure ImageMagick policy.xml
sudo cp ${DIR}/policy.xml /etc/ImageMagick-6/policy.xml

#front side
sudo apt-get install -y php7.2-fpm
sudo apt-get install -y nginx

sudo cp ${DIR}/templates/site-rsd.conf /etc/nginx/sites-available/rsd.conf
sudo ln /etc/nginx/sites-available/rsd.conf /etc/nginx/sites-available/rsd.conf

sudo cp ${DIR}/templates/php.ini /etc/php/7.2/fpm/php.ini

sudo apt-get install -y acl isc-dhcp-server bind9

sudo apt-get install -y vim mc

sudo apt-get install -y sqlite3 php7.2-sqlite3

sqlite3 ../database.sqlite < ./templates/database.sql

echo 'unpack bind and dhcpd configuration'

