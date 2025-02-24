#BACKEND
___

1. Zip the server and upload in cpanel file manager.
2. In Cpanel, create public_html
3. outside the public_html of the domain, unzip the server and rename to [api] (optional)
4. in public_html, create index.php and .htaccess file.
5. required the api/index.php in the public_html/index.php
6. reroute the requests for public_html/index.php to the api/index.php using `.htaccess`

#FRONTEND
___

1. Double check the env file and make sure that the API_URL is pointed to right endpoint
2. Build the react using npm run build 
3. upload the zip file in cpanel
4. unzip, and rename the folder react or any rename
5. using .htaccess, reroute all non api requests to react folder


