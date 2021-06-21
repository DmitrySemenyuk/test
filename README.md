# setup
1) Create google app, with right rights.
2) Generate `credentials.json` there, and put it in `app/config/googleSheets`. Path can be changed in 
   section `parameters` in file `service.yaml`.
3) Put file `coffee_feed.xml` in `app/nfs`.

# environment
Docker-compose.yml consist of 2 containers: php and nginx. 
To run app:
1) Use `docker-compose up -d --build`. 
2) Then go to container `docker exec -it productsup_php bash`.
3) Install dependency by running command `composer install`.   
4) Run `bin/console app:read-file {src}` to parse file `coffee_feed.xml` and send data to google spreadsheet.
   First time command will interact with you, and you will need to get the token.

# architecture
Console command consist of 4 logical steps.
- download file
- parse xml file
- transform data
- save data in spreadsheet 
From my perspective, better to cut first step in separate command. That will give more resilience to app. 
Step `transform data` makes no sense in the current state, it exists only to show that in bigger app developers can use
DTO and work with clear structured data. For DTO I don't use getters and setters. You can find more info in
section `Avoid writing naive setters and getters` 
https://web.archive.org/web/20140625191431/https://developers.google.com/speed/articles/optimizing-php
Step `save data in spreadsheet` contains copy&paste from google documentation.

# logging
Very important part of the system, represented here in bad shape. Every error saved to same log file.
More wisely will be use unique errors for each type of failure, and send them to modern monitoring system.
It will help eliminate problems fast.

# tests
I wrote a couple of Unit and Integration tests.
I didn't write the Application test, because for this I will need to implement additional logic: 
as download file from google, check it and delete. This will take additional time, which I don't have.
