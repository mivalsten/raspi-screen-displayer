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

sudo apt-get install -y samba imagemagick libreoffice ffmpeg

#configure ImageMagick policy.xml
sudo cp ${DIR}/policy.xml /etc/ImageMagick-6/policy.xml

#TODO: automatic samba configuration

sambaConfig="
[videoOutput]
	directory mode = 777
	guest only = yes
	guest ok = yes
	path = ${output}
	comment = Video output
	public = yes
	create mode = 777
	writeable = no
"