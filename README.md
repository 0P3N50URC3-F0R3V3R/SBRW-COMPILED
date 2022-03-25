# SBRW-COMPILED
this is the NFSW world (Soapbox Racing) precompiled version, all it needs just a database and openfire setup.
For the lazy who wdo not want to read on to the bottom of this page: Based on this project: https://github.com/SoapboxRaceWorld
Also there is a link for the VM in the relases description for the precondifured SDK envrionment.

NOTE: There is a portable server too, if you want to use that, all you need to edit the server's IP adresses from 192.168.1.12 to your server's ip address.
You will need to install redis though. cdn and other stuffs are optional if you are not willing to use your own client. Mods are using SHA1 crc check and you ca nedit mods with winrar.

Check the RELASES section ---------->

This server is HIGHLY customisalble throught MYSQL, or with mod tools you can use microtransactions or not... you can customize cars, packs, parts ect... It has mod tools also but not in this pack.

What is in the package:

- Openjava10 - Requied for running
- Openfire 4.5.0 Alpha - THIS IS NOT REPLACEABLE WITH THE OFFICIAL OPENFIRE VERSIONS!!! THIS IS CUSTOM BUILT FOR THE SERVER!!! you will need to install it yourself. Details on the tutorial below.
- MYSQL 8.0.18 - you will need to install this, no exeptions. (you can try a portable though, but i would recommend it to install)
- The sample Database (THAT IS NOT provided by anywhere by default.)
- The server files (Core, Freeroam, Race Server)

WARNING! THIS TUTORIAL AND PACK IS JUST FOR PRIVATE USE! IT IS NOT SAFE TO RELY ON PRODUCTION USE, THAT WILL NEED SOME EXTRA SECURITY PRECAUTIONS. If you follow this; it will work. Thats it; you can play on lan or privately. All other security issue you need to fix it.

You will need navicat or mysql workspace, or heidiSQL.
YOU WILL NEED ADMINISTRATOR RIGHTS FOR THIS WHOLE TUTORIAL!

THIS IS FOR WINDOWS!!!

How to install this:
Spoiler: 

0. Download, and Firstly, extract the Pack into(for the sake of simplicity) to C:\ then you will have C:\SBRW

1. Install MYSQL 8.0.18 from C:\sbrw\Database\MySQL Programs\mysql-installer-community-8.0.18.0.msi
Install as you see fit, (Use legacy passwords at install when the server asks it)

2. Log in with navicat, heidisql or workbench, and import the sql files, all 3 in order 1-2-3 all sql files.
From: "sbrw\Database\MySQL scripts"

3. Extract the java to C:\(it has to be there) to the final extracted program will look like this: C:\AdoptOpenJDK10\bin\

4. Edit c:\sbrw\core\project-defaults.yml and change the password: line value to your mysql's newly installed root password. save and close the file.

5. start C:\sbrw\openfire\bin\sbrwopenfirelauncher.bat (if you are extracted the java to the correct place; then it will work.) After it booted(wait until it is loads up) then load up your favorite browser on http://localhost:9090 and process thru the installation process....

When you are at the database, select mysql and the connection string paste this:
Code:

jdbc:mysql://localhost:3306/openfire?rewriteBatchedStatements=true&characterEncoding=UTF-8&characterSetResults=UTF-8&serverTimezone=UTC&useSSL=false


and below that use user root and password for the mysql. Process thru the openfire installation and log in with admin account(Username admin, password what you will give during the installation)

6. In openfire:

- Go into server settings, go to registrations&login set Inband Accopunt registration to Disabled and SAVE settings below.

- Go to Copmression settings and put all 2 options to not avaliable. and press Save settings

- Go to the REST API(SBRW) and set Rest api Enabled, and set the Secret key auth and copy the random generated key (this is a random key and it will need to be in the mysql database) And press save.
You can close openfire. BUT YOU NEED THE KEY!!!

7. Go into your favorite MYSQL editor and open Soapbox database and parameter table in mysql editor.
Search the line: OPENFIRE_TOKEN and at the value field change the text to your secret key from openfire rest api!!!

- In this same database set theese values where is [to your server's IP] change it to your IP, like 127.0.0.1 or such:

Change existing values:

Code:
- ENABLE_REDIS false
- PORTAL_DOMAIN [to your server's IP]
- PORTAL_FAILURE_PAGE [to your server's IP]/nfsw-portal-error
- SERVER_ADDRESS http://[to your server's IP]
- UDP_FREEROAM_IP [to your server's IP]
- UDP_FREEROAM_IP [to your server's IP]
- UDP_RACE_IP [to your server's IP]
- XMPP_IP [to your server's IP]
- STARTING_LEVEL_NUMBER 1

Now. You need to add theese fields and values manually(this will enable powerups and multiplayer ingame):

Code:
- MODDING_BASE_PATH    http://[to your server's IP]
- MODDING_ENABLED    true
- MODDING_FEATURES    ""
- MODDING_SERVER_ID [your server id] whatever can this be not visible.

RUN THIS SCRIPT IN MYSQL TO FIX GEM COLLECTING EVENT PARTIALLY:
- CREATE TRIGGER setDate_INSERT BEFORE INSERT ON treasure_hunt FOR EACH ROW SET NEW.thDate = ADDDATE(curdate(), INTERVAL 0 DAY);
- CREATE TRIGGER setDate_UPDATE BEFORE UPDATE ON treasure_hunt FOR EACH ROW SET NEW.thDate = ADDDATE(curdate(), INTERVAL 0 DAY);

8. Shutdown openfire server in background(close the window)

9. Now. IF and just IF you did everything right, you CAN now startup the server, but i warn you the server core WILL crash for the first 2 times. (its a bit hectic) if that occours(AKA core window will close after boot)
close everything and start again, it will work for the 2 or 3rd time. After that; it is far more stable.
So to start the server start : C:\sbrw\start-sbrw.bat

It will start openfire, miniweb server(it needs for the mods) race and freerun server and core for the last.

10. Client. Now. use official client, from here: https://github.com/SoapboxRaceWorld/...eleases/latest OR you can alternately use the provided CDN by replacing htdocs folder with the extracted cdn directory.


Extract and start, select where to download the client and in the launcher add your server by clicking to the + button at the top right and in the next window press add server, and fill out like this and to your_ip_here change to your your server's ip adress and add a custom category(you can name it however you want).
http://your_ip_here:8080/Engine.svc

11. Restart the launcher.

12. Select your server from the dropdown list and press register, use an email, password and register. WAIT FOR THE CLIENT FINISH DOWNLOADING!

13. Login to your server, then press play... AAAAAND if everything goes well it will startup the game and
you can create a driver buy a car, and login.

14. You will be asked to race thru a sprint race WITHOUT any powerups, do it finish it. After it is finished in freeroam test if powerups are working(EG: Nitro, or such) if it does then you are good... if not; then you need to go back to step 7 modding section.(aslo make sure if the miniweb server is running and port 80 is free)


You can customize everything... Some standard hints in mysql:

- event - > Races and events type, almost half of the mare turned off, you can turn in them if you want
- products - > ALL store items like booster packs, cars carparts ect... pretty self xplanatory... EG: if you want, you can change all currency to simple cash... (CASH - Normal -money _NG - Booster money)
- persona - > you can edit the characters here, add booster and money points experience ect ect...

To fix friend system:
Use the redis for friend system from relases:Needed for the friend system to work.
(it needed to be running in the background and enable redis in the MYSQL->soapbox->parameters->redis=true )

I used this: https://github.com/berkayylmao/setting-up-sbrw for the tutorial
and used some parts of theese:

SOURCE CODE: https://github.com/SoapboxRaceWorld

All credits goes to them.
