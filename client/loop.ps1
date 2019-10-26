#!/usr/bin/pwsh

$server = 'display.ien.pw.edu.pl'

if (test-path -path "$PSScriptRoot/checksum.md5" -PathType Leaf) {
    $oldChecksum = [string](Get-Content $PSScriptRoot/checksum.md5) -replace '\s+', ''
} else {
    $oldChecksum = ''
}
try {
    $newChecksum = $(Invoke-WebRequest -Uri "http://$server/out/incomingChecksum.md5").Content -replace '\s+', ''
}
catch {
    $newChecksum = ''
}
if ("$oldChecksum" -ne "$newChecksum") {
    write-output "!!$oldChecksum!!"
    write-output "!!$newChecksum!!"
    try {Invoke-WebRequest -Uri http://$server/out/final.mp4 -OutFile $PSScriptRoot/newFinal.mp4}
    catch {exit 1}
    $newChecksum | Out-File $PSScriptRoot/checksum.md5
    Get-Process omxplayer* | Stop-Process
    Move-Item -path "$PSScriptRoot\newFinal.mp4" -Destination "$PSScriptRoot\final.mp4" -Force
}

if ($(Get-Process omxplayer*).count -eq 0) {
    Start-Process -filepath omxplayer -argumentList "--loop -b $PSScriptRoot/final.mp4 &"
}

