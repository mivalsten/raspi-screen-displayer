#!/bin/bash

####################################################################
#                                                                  #
#Script Name       : main.sh                      				   #
#Description       : Main script used to normalize				   # 
#					 and join media files						   #
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

stagingImageType=".png"
framerate=25

#directories
incoming="${rootPath}/in"
stagePDF="${rootPath}/staging/1pdf"
stageImage="${rootPath}/staging/2image"
stageVideo="${rootPath}/staging/3video"
output="${rootPath}/out"

###########################################################
#                                                         #
#                   Regular Expressions                   #
#                                                         #
###########################################################

documentRegex=".*\.\(pptx?\|docx?\|odt\|odp\)"
videoRegex=".*\.\(webm|mkv|flv\|flv\|vob\|ogv\|ogg\|drc\|gif\|gifv\|mng\|avi\|MTS\|M2TS\|mov\|qt\|wmv\|yuv\|rm\|rmvb\|asf\|amv\|mp4\|m4p\|m4v\|mpg\|mp2\|mpeg\|mpe\|mpv\|mpg\|mpeg\|m2v\|m4v\|svi\|3gp\|3g2\|mxf\|roq\|nsv\|flv\|f4v\|f4p\|f4a\|f4b\)"
imageRegex=".*\.\(png\|gif\|jpe?g\|webm\)"

###########################################################
#                                                         #
#                       Script Block                      #
#                                                         #
###########################################################

#cleanup working directories
rm -rf ${stagePDF}/*
rm -rf ${stageImage}/*
rm -rf ${stageVideo}/*
rm -rf ${output}/*

# fix filenames
cd ${incoming}
for file in *.*; do
	extension="${file##*.}"
	newFilename=`echo "${file%.*}" | sed 's/[^a-zA-Z0-9\/]//g'`
	newFilename="${newFilename}.${extension}"
	mv "${file}" "${newFilename}"
done

# calculate MD5 of input directory to check for changes
# if not same, execute rest of the script
# command taken from https://stackoverflow.com/questions/1657232/how-can-i-calculate-an-md5-checksum-of-a-directory
oldChecksum=`cat ${rootPath}/incomingChecksum.md5`
newChecksum=`find ${incoming} -type f -name "*" -exec md5sum {} + | awk '{print $1}' | sort | md5sum | cut -d' ' -f1`

echo "old ${oldChecksum}"
echo "new ${newChecksum}"

if [[ "${oldChecksum}" = "${newChecksum}" ]]; then
	echo "no changes detected, exiting"
	exit 0
else
	echo "${newChecksum}"  > ${rootPath}/incomingChecksum.md5
fi

#find office documents in incoming directory and convert them to PDF for stage one conversion
echo "converting documents to PDFs"
find ${incoming} -maxdepth 1 -iregex "${documentRegex}" | while read name; do
	libreoffice --convert-to pdf "${name}" --outdir "${stagePDF}"
done

#copy PDF files to include them in further processing
find ${incoming} -maxdepth 1 -name "*.pdf" -exec cp {} ${stagePDF} \;

#convert pdf files to images for stage 2 conversion
find ${stagePDF} -maxdepth 1 -name "*.pdf" -exec basename "{}" .pdf \; | while read name; do
	convert -background white -alpha off -density 400 "${stagePDF}/${name}.pdf" "${stageImage}/${name}.png"
done

#convert other images to png for further processing
find ${incoming} -maxdepth 1 -iregex "${imageRegex}" -exec basename "{}" \; | while read name; do
	convert -background white -alpha off -coalesce -density 400 "${incoming}/${name}" "${stageImage}/${name}.png"
done

#TODO: add separate section for moving images

# Create 10 second video from each single image for stage 2 conversion
# source: https://trac.ffmpeg.org/wiki/Slideshow
cd ${stageImage}
for name in *${stagingImageType}; do
	ffmpeg -v fatal -loop 1 -i "${stageImage}/${name}" -c:v libx264 -preset ultrafast -t 10 -vf scale="1920:1080:force_original_aspect_ratio=decrease",pad="1920:1080:(ow-iw)/2:(oh-ih)/2",setsar=1 -pix_fmt yuv420p -y -r ${framerate} "${stageVideo}/${name}.mp4"
done

# get videos from input and normalize them (1920x1080 + black bars if necessary)
# for some reason, only first run was executing correctly, puting them in background solves the issue #bashIsWeird
# at the end to let conversions finish
cd ${incoming}
find ${incoming} -maxdepth 1 -iregex "${videoRegex}" -exec basename {} \; | sort | while read name; do
	filename="${name%.*}"
	ffmpeg -v fatal -i "${incoming}/${name}" -c:v libx264 -preset ultrafast -vf scale="1920:1080:force_original_aspect_ratio=decrease",pad="1920:1080:(ow-iw)/2:(oh-ih)/2",setsar=1 -pix_fmt yuv420p -y -r ${framerate} "${stageVideo}/${filename}.mp4" &
done

while [[ `ps -ef | grep ffmpeg | wc -l` -gt 1 ]]; do
	echo waiting... >> ${rootPath}/debug.log
	sleep 1
done

ffmpeg -v fatal -f concat -safe 0 -i <(find ${stageVideo} -name '*.mp4' -printf "file '%p'\n" | sort) -s hd1080 -c:v libx264 -acodec copy -an -strict -2 -y -r ${framerate} ${output}/out.mp4
