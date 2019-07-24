#php editable start
$rootPath="/srv/inz" #string
$stagingImageType=".tiff" #string
$framerate=25 #int
$slideDurationSeconds=5 #int
#php editable end

#directories
$incoming="${rootPath}/in"
$stage="${rootPath}/staging"
$stagePDF="${stage}/1pdf"
$stageImage="${stage}/2image"
$stageVideo="${stage}/3video"
$output="${rootPath}/out"
$www="${rootPath}/www"
$wwwUploads="${www}/uploads"