# raspi-screen-displayer

This is a set of utilities that bundles documents, images and videos together to create one video file with no sound to play on raspberryPi.
Raspi scripts are in /client folder, put them in cron every so often (I used * * * * *) 

How to use:

Pull to local, install php and your favourite webserver. Add execution right to scripts from ./scripts

Run ./scripts/setup.sh

Edit and execute ./scripts/config.ps1

Edit ./www/config.php

Configure your webserver with ./www as a root document.

Default user:password is admin:admin, change after first logon.

Probably some permissions are incorrect, run ./scripts/main.sh to find out which.

Basically this. Control panel is pretty basic, so you should be able to figue it out yourself.

Schedules are set in a way that allows only first valid schedule to be activated.

./scripts/main.ps1 should be in cron.
