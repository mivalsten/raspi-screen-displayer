#!/snap/bin/pwsh

####################################################################
#                                                                  #
#Script Name       : scheduler.ps1                                 #
#Description       : Used to link input directory to scheduled     #
#                    documents                                     #
#Author            : Grzegorz Kawka-Osik                           #
#Version           : 1.0                                           #
#Creation date     : 04.01.2018                                    #
#                                                                  #
####################################################################

#. "$PSScriptRoot/config.ps1"

$sched = "default"
$scheduleFile = "$PSScriptRoot/schedule.txt"
$CurrentDate = Get-Date
$loc = [System.TimeZoneInfo]::Local
$utc = [System.TimeZoneInfo]::Utc


foreach ($schedule in get-content $scheduleFile) {
    $sch, $startEpoch, $endEpoch = $schedule -split ';'
    $startEpoch = [system.TimeZoneInfo]::ConvertTime((Get-Date 01.01.1970) + ([System.TimeSpan]::fromseconds($startEpoch)),$utc,$loc)
    $endEpoch = [system.TimeZoneInfo]::ConvertTime((Get-Date 01.01.1970) + ([System.TimeSpan]::fromseconds($endEpoch)),$utc,$loc)
    if ($startEpoch -le $CurrentDate -and $currentDate -le $endEpoch) {
        $sched = $sch
        break
    }
}

Write-Output "Linking $wwwUploads/$sched at $incoming"
New-Item -Type SymbolicLink -Path $incoming -Value $wwwUploads/$sched -Force | Out-Null
