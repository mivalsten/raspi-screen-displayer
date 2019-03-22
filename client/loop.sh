#!/bin/bash

####################################################################
#                                                                  #
#Script Name       : loop.sh                                       #
#Description       : client script responsible for                 #
#                    fetching and playing video file               #
#Author            : Grzegorz Kawka-Osik                           #
#Version           : 1.0                                           #
#Creation date     : 18.03.2018                                    #
#                                                                  #
####################################################################

server='display.ien.pw.edu.pl'
scriptRoot='/home/pi'

oldChecksum=`cat ${scriptRoot}/checksum.md5`
newChecksum=`curl -s http://${server}/out/incomingChecksum.md5`
exitCode=$?

if [ ${exitCode} != 0 -o "${newChecksum}" == "" ]; then
	exit 0
fi

#if checksums match, quit without making changes
if [ "${oldChecksum}" = "${newChecksum}" ]; then
	echo "no changes, exiting"
	#if video is not playing, start playing
	if [ `ps -ef | grep omxplayer | wc -l` -le 1 ]; then
		omxplayer --loop ${scriptRoot}/final.mp4 &
	fi
	exit 0
else
	echo ${newChecksum} > ${scriptRoot}/checksum.md5
fi

#download new video
curl -s http://${server}/out/final.mp4 -o ${scriptRoot}/newfinal.mp4

#kill omxplayer
killall omxplayer.bin

#switch files
rm ${scriptRoot}/final.mp4
mv ${scriptRoot}/newfinal.mp4 ${scriptRoot}/final.mp4

#play new file
omxplayer --loop ${scriptRoot}/final.mp4 &


