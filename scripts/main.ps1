#!/snap/bin/pwsh

$root = $PSScriptRoot
$FFMPEGVerboseLevel = "info"

. $root/config.ps1

$documentRegex = ".*\.(pptx?|docx?|odt|odp)"
$videoRegex = ".*\.(mkv|vob|ogv|ogg|drc|gifv|mng|avi|MTS|M2TS|mov|qt|wmv|yuv|rm|rmvb|asf|amv|mp4|m4p|m4v|mpg|mp2|mpeg|mpe|mpv|mpg|mpeg|m2v|m4v|svi|3gp|3g2|mxf|roq|nsv|flv|f4v|f4p|f4a|f4b)"
$imageRegex = ".*\.(png|gif|jpe?g|webm|tiff)"

if ( $(ps -ef | grep pwsh | wc -l) -gt 2 ) {
    write-output "Script already running, exiting."
    Write-Output $(ps -ef | grep pwsh)
    exit 0
}
$ErrorActionPreference = "SilentlyContinue"
remove-item -Path "$stagePDF/*"
remove-item -Path "$stageImage/*"
remove-item -Path "$stageVideo/*"
#remove-item -Path "$output/final.mp4"
$ErrorActionPreference = "Continue"
#run scheduler, for later

#remove whitespaces from filenames
foreach ($file in get-childitem -Path $incoming -File) {
    if ($file.basename -match '[^a-zA-Z0-9]') {
        Rename-Item -Path $file.fullname -NewName "$($file.basename -replace '[^a-zA-Z0-9]')$($file.extension)"
    }
}

#check if there were any changes
$checksumPath = "$output/incomingChecksum.md5"
if (Test-Path $checksumPath -PathType Leaf) { $oldChecksum = get-content $checksumPath }
else { $oldChecksum = '' }
$newChecksum = $(foreach ($file in gci $incoming) { Get-FileHash $file }) | Sort-Object -Property "path" | md5sum

if ($oldChecksum -eq $newChecksum) {
    Write-Output "No changes detected."
    exit 0
}
else {
    Write-Output $newChecksum | Out-File -FilePath "$output/newIncomingChecksum.md5"
}

#convert documents to PDFs
foreach ($file in $(Get-ChildItem -Path $incoming | where-object -FilterScript { $_.name -match $documentRegex })) {
    Write-Output "Converting $($file.fullname) to PDF"
    libreoffice --convert-to pdf "$($file.FullName)" --outdir "$stagePDF"
}

#copy PDFs to stage directory
Get-ChildItem -Path $incoming -Filter "*.pdf" | copy-item -Destination $stagePDF

#convert PDFs to series of images
foreach ($file in $(get-childitem -path $stagePDF)) {
    write-output "converting $file to image(s)"
    convert -background white -alpha off -density 300 -resize 1080x "$($file.FullName)" "$stageImage/$($file.BaseName)-%04d$stagingImageType"
}

#convert other images to png for further processing
Get-ChildItem -Path $incoming | where-object -FilterScript { $_.name -match $imageRegex } | ForEach-Object {
    convert -background white -alpha off -coalesce -density 200 "$($_.FullName)" "$stageImage/$($_.BaseName)$stagingImageType"
}

# Create n second video from each single image for stage 2 conversion
# source: https://trac.ffmpeg.org/wiki/Slideshow
foreach ($file in $(Get-ChildItem -path $stageImage)) {
    write-output "converting $file to video"
    $fadeStart = ($slideDurationSeconds - 1) * $framerate
    ffmpeg -v $FFMPEGVerboseLevel -loop 1 -i "$($file.FullName)" -c:v libx264 -preset ultrafast -t $slideDurationSeconds -vf scale="1920:1080:force_original_aspect_ratio=decrease",pad="1920:1080:(ow-iw)/2:(oh-ih)/2",setsar=1,fade=in:0:$framerate,fade=out:$fadeStart`:$framerate -pix_fmt yuv420p -y -r $framerate "$stageVideo/$($file.BaseName).mp4"
}

Get-ChildItem -Path $incoming | Where-Object -FilterScript { $_.Name -match $videoRegex } | ForEach-Object {
    ffmpeg -v $FFMPEGVerboseLevel -i "$($_.fullName)" -c:v libx264 -preset ultrafast -vf scale="1920:1080:force_original_aspect_ratio=decrease",pad="1920:1080:(ow-iw)/2:(oh-ih)/2",setsar=1 -pix_fmt yuv420p -y -r ${framerate} "${stageVideo}/$($_.BaseName).mp4"
}

#create concat file
Get-ChildItem -Path $stageVideo | ForEach-Object {
    Write-Output "file '$($_.FullName)'`n" | Out-File -FilePath "$stageVideo/concat.txt" -Append
}

while ($(ps -ef | grep ffmpeg | wc -l) -gt 0) {start-sleep -Seconds 1}

ffmpeg -v $FFMPEGVerboseLevel -f concat -safe 0 -i "$stageVideo/concat.txt" -s hd1080 -c:v libx264 -acodec copy -an -strict -2 -y -r $framerate $output/newfinal.mp4
