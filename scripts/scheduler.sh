#!/bin/bash

####################################################################
#                                                                  #
#Script Name       : scheduler.sh                                  #
#Description       : Used to link input directory to scheduled     #
#                    documents                                     #
#Author            : Grzegorz Kawka-Osik                           #
#Version           : 1.0                                           #
#Creation date     : 04.01.2018                                    #
#                                                                  #
####################################################################

###########################################################
#                                                         #
#                 Configuration Variables                 #
#                                                         #
###########################################################

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

. "${DIR}/config.sh"

scheduleFile="${DIR}/schedule.txt"
noSched=1

if [[ -e "${incoming}" ]]; then
	rm "${incoming}"
fi

for line in `cat ${scheduleFile}`; do
    sch=`echo ${line} | cut -d ';' -f 1`
	startEpoch=`echo ${line} | cut -d ';' -f 2`
	endEpoch=`echo ${line} | cut -d ';' -f 3`
	currentEpoch=`date +%s`
	if [[ "${startEpoch}" -le "${currentEpoch}" && "${currentEpoch}" -le "${endEpoch}" ]]; then
		echo linking "${wwwUploads}/${sch}" in "${incoming}"
		ln -s -f "${wwwUploads}/${sch}" "${incoming}"
		noSched=0
		break
	fi
done

if [[ "${noSched}" -eq "1" ]]; then
	echo linking "${wwwUploads}/default" in  "${incoming}"
	ln -s -f "${wwwUploads}/default" "${incoming}"
fi
