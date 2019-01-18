#!/bin/bash

# below section should keep format of
# "variableName=variableValue #variableType (string|int) #unit(empty if none)"
# to ensure that it's properly handled by PHP frontend

#php editable start
rootPath="/srv/inz/" #string #
stagingImageType=".png" #string #
framerate=25 #int #
slideDurationSeconds=10 #int #s
#php editable end


#directories
incoming="${rootPath}/in"
stage="${rootPath}/staging"
stagePDF="${stage}/1pdf"
stageImage="${stage}/2image"
stageVideo="${stage}/3video"
output="${rootPath}/out"
www="${rootPath}/www"
wwwUploads="${www}/uploads"
