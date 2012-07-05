#shortenURL Application#
=======================

Jacques Woodcock - [jacques@kitewebconsulting.com](jacques@kitewebconsulting.com)

[Blog](http://jacqueswoodcock.tumblr.com/)

This is an application written to provide URL shortening from outside applications through a semi-RESTful interface.

About this project
-----------------
This is a multi-client, multi-user URL Shortener that is not 100% functional around these goals. Though the foundation to support multiple clients using client specific logins per single user is there, work needs to be done to complete this functionality. 

This URL shortener was created as a method to exercise some concepts on REST, Sharding and single application controlers. It was done early in my understanding of REST and thusly, has a number of REST flaws, which is obvious through the resource URI structure listed below.

Since it was coded, I've started doing OOP, using design patterns and other advanced techniques so the code needs to be refactored. 

I am interested in all advice around refacting this, pointing out of potential programic issues and other advice. 

Usage
-----

### Database
In the DB folder is the structure of the database that supports this application. Import the SQL into your database then create a database connection file and save as a file. Once created, import that file into the main processor.php script on line 17.

### IPs and ShortenerURL
There are two variables you need to define at the top of the processor.php script, $ips and $shortenerURL.
`$ips` are IP Address that are are allowed to query the REST services.
`$shortenerURL` is the address of your shortener so the application know to only process redirects from this source.

### URI
There are 3 action  URIs to this application.

1. Shorten URL Redict
    http://yourshorturl.co/shorthash
This takes a provided shortened URL, looks up the long URL, grabs some stats from the visitor and stores them in the database, then redirects the visitor. 

2. Shorten URL 
    POST to http://yourshorturl.co 
    Requires POST variables
        `url` - url to be shortened
        `cl`  - client id 
This takes a provided URL and client id, shortens the URL and associates it with the client record then returns the shortened URL as XML or JSON

3. REST Calls
The REST calls are accessed through querying the /api/ uri `http://yourshorturl.co/api`. 

The calls are 

    /api/urls
    Returns all the latest URLs that have been shortened

    /api/clients
    Not built out as of yet

    /api/hits
    Returns the hits of a particular URL 

    /api/hits_by_cookie
    Returns hits associated with  a cookie query

    /api/hits_by_ipaddress
    Returns hits associated wit an ipaddress

    /api/hits_by_date
    Returns hits matching a date or date range

    /api/hits_by_bots
    Returns hits matching a provided bote, or user agent

    /api/heartbeat/
    Status resource, can accept POST or GET variables and returns them to show service is up and working

    /api/authen/
    Resource to authenticate client and user

### Authentication
Authentication is done by defining a api credentials per client within the api_logi table and associating it to a client within the cl_client table. 

REST service checks against the api_logi table whereas admin users, or customers, should be checked against the cl_auth table. 

REST authentication passwords are salted with a secret phrase which is stored in the cl_client table, sk column. 


 
