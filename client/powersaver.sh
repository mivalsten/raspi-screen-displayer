#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
server='display.ien.pw.edu.pl'

times=`curl -s http://${server}/out/client.conf`
exitCode=$?                                                    
                                                               
if [ ${exitCode} = 0 ]; then
	echo $times
	echo $times > ${DIR}/times.conf
fi

. $DIR/times.conf

#

# placeholder end

begin=$(date --date="${screenOn}" +%s)
end=$(date --date="${screenOff}" +%s)
now=$(date +%s)

if [ "${begin}" -gt "${now}" ]; then
	#turn screen off
	echo standby 0 | cec-client -s -d 1
	echo "shutting down tv"
fi
if [ "${begin}" -le "${now}" -a "${now}" -le "${end}" ]; then
	#turn screen on
	echo on 0 | cec-client -s -d 1
	echo "starting tv"
fi
if [ "${now}" -gt "${end}" ]; then
	#turn screen off
	echo standby 0 | cec-client -s -d 1
	echo "shutting down tv"
fi
