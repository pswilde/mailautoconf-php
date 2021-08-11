# MailAutoConf - a simple, configurable autodiscover/autoconfig service for distributed and self-hosted services.  

## What is MailAutoConf?
MailAutoConf is autodiscover/autoconfig web server for self-hosted mail services
which do not have their own autodiscover service.

## What does MailAutoConf do?
MailAutoConf is currently in _very_early stages, but it does generate valid
autoconfig XML files (/mail/config-v1.1.xml) compatible with many mail clients
i.e. Thunderbird, Evolution, etc.  
Theoretically, anything that can read the standard autoconfig XML file -
https://wiki.mozilla.org/Thunderbird:Autoconfiguration:ConfigFileFormat, should
be able to auto-configure using this service.

## Installation
The container file includes set up for an apache webserver to run the application.
You will need to supply a volume for the configuration file and port forwarding.
```
podman run -dt \
  --name mailautoconf \
  -v ./config:/var/www/html/config \
  -p 8010:80 \
  pswilde/automailconf
```
You will need a reverse proxy server to publish to the outside world and handle SSL encryption.  
For example, in nginx:
```
server {
        listen      443 ssl;
        server_name  autoconfig.example.com;
	      ssl_certificate		/path/to/certificate.file;
	      ssl_certificate_key	/path/to/certificate.key
        location / {
		      proxy_set_header X-Forwarded-Host $host:$server_port;
    		  proxy_set_header X-Forwarded-Server $host;
    		  proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
		      proxy_pass 	     http://localhost:8010/;
		      proxy_redirect 	 http://localhsot:8010/ /;
		      proxy_read_timeout 60s;
        }
    }
```

## Future plans
MailAutoConf is currently in _very_ early stages, with a _very_ limited set of features.
My hope for MailAutoConf is to mimic the AutoDiscover service found in Microsoft Exchange services,
but with the intent of providing a set of URLS for each service which may be self-hosted and/or distributed,
primary IMAP, SMTP, CalDAV and CardDAV URLS, but hopefully more services can be added in the future.  

__For example:__  
Company X may have many services that are selfhosted i.e. Dovecot for email (IMAP/SMTP) and Nextcloud for Calendar and Contacts.
Getting a set up like this configured on a mobile device is fairly involved for a standard user and tiresome if the company has many employees.
There are many points where set up configuration mistakes can happen, leading to service outage for a user, and the difficult job of
the IT consultant trying to talk the user through setting the device up over the phone.

MailAutoConf intends to patch this problem by providing the URLs and information (Port numbers, SSL/TLS type, domain name, etc.) for each service
in JSON format allowing for the connecting device to automatically set up this information on the device.

## What problems do I expect?
OK, the most glaringly obvious problem is that the JSON response information is no good if the device itself doesn't know what to do with it.  
I hope to develop a mobile app with mail, contact and calendar features in it, but we're in _very_ early stages of the base service at the moment.
Once the basic JSON response service is operational, I'll look into the mobile app which will then be when things will start to become useful.  

In the perfect world, this service starts to look so fantastic that mobile device Operating System and/or mail app developers start implementing the option
in their own code as an Account Type (i.e. ActiveSync, Office365, iCloud, IMAP, etc. are all already there), but I'm not sure if I see that happening just yet.  

### Another problem is authentication.
I'd like all clients to authenticate to the MailAutoConf service, but where do we get that authentication from?  
We could have local accounts on the MailAutoConf server obviously, but I don't think this feels "fluent" enough. Maybe, using the primary IMAP server address, we could do an authentication request and if that succeeds the login is accepted and MailAutoConf information is sent.   

### More problems regarding authentication.
As we're intended to be used for self-hosted, distributed services, each service may have different usernames and passwords.
This means there will have to be some sort of manual credential entry for each service.  
I am less concerned with this issue as it currently isn't really in the scope of MailAutoConf to handle this - the goal is to provide the core information (URL, Ports, etc.) not the credentials to log in. I of course want to make the experience as helpful as possible though, so I'll deal with any features surrounding this when I can.

## When will it be ready for production?
Well, not yet.  
I'm working on this ultimately for my own use for my own small business. I'm hoping once it's good enough I could deploy the set up to customers
and ultimately get them away from a Microsoft Exchange based environment. There's a long way to go for that right now though.  

If you feel you may be able to help, or ideas on features and their implementation, notice any bugs, or just want to say hi. Please do so and submit a pull request if required.

Thanks for reading!
