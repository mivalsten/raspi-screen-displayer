# raspi-screen-displayer

This is a set of utilities that bundles documents, images and videos together to create one video file with no sound to play on raspberryPi.
As of now, the RaspberryPi playing part is not yet complete.

How to use:

Pull to local, install php and your favourite webserver. Add execution right to scripts from ./scripts

Edit and execute ./scripts/config.sh

Edit ./www/config.php

Configure your webserver with ./www as a root document.

Configure usernames and passwords (plaintext xD) in ./www/login.php. This will get fixed sometime to use nginx pam module or something.

Probably some permissions are incorrect, run ./scripts/main.sh to find out which.

Basically this. Control panel is pretty basic, so you should be able to figue it out yourself.

Schedules are set in a way that allows only first valid schedule to be activated.

./scripts/main.sh should be in cron.
