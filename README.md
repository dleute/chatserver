#Chat Server
Chat server is a proof of concept real time communication server written in php

### System Requirements

The basic requirements are outlined below. Making a compatible server is beyond the scope of this guide.

#### Hosting

* PHP >= 5.4 < 5.6
* Symfony >= 2.6-Beta
* ZeroMQ system library (for real-time socket based messages)
* ZeroMQ php library (installable via pecl)
* MySQL or Sqlite for DB (depends on the environment, sqlite is not configured by default)
* PHP composer

#### Dev/Deployment

* Ruby (tested with 2.1+)
* Capistrano (Ruby gem)
* Capifony (Ruby gem for capistrano which only works with Capistrano 2 and not 3)
* capistrano_rsync_with_remote_cache (Ruby gem for rsync copies)
* Selenium2 for functional testing (included in git)
* ChromeDriver for Selenium2
 
Symfony requirements can be checked via:

`php app/check.php`

Composer can be installed:

`curl -sS https://getcomposer.org/installer | php`

In this guide composer is installed globally by moving it into the path as `composer`

### Installation

#### Dev System

* Clone this repository - `git clone git@github.com:dleute/chatserver.git myfolder`
* In that folder Run composer install - `composer install`
* Setup local hosts file with domain if necessary
* Setup apache or use the php built-in server (beyond scope)
* Copy app/config/parameters.yml.dist to app/config/parameters.yml
* Edit app/config/parameters.yml for your db systems (composer may prompt you for this)

### Usage

chatserver.allofzero.com is the domain used in this example. Please replace with your own domain (or use it in hosts which may be easier)
chatdeploy.allofzero.com is the domain used for live deployment.

#### Pre-loading sample data

For usage and testing a fixtures file is included that creates a few users for you.

`./app/console doctrine:fixtures:load` - WARNING: this will destroy the current database and re-populate it

#### Creating users manually

On the system that needs additional users:

`./app/console fos:user:create` and follow the prompts. (be sure to provide the environment if you use different db config)

#### Running the socket server

The socket server allows clients to connect and get real-time notification of others activity.

The following command takes 2 ports. Please use these as they are hard coded in some places for now. One port accepts communication from clients (8080) and the other listens for messages from the app server to send to clients (5555).

`./app/console chat:server 8080 5555` - By default this command will launch in the dev environment and enable debugging.

`./app/console chat:server 8080 5555 -e prod` - Will launch in the prod environment with no debugging

#### Try it out

If everything is configured, going here will show you the welcome page:

* go to `http://chatserver.allofzero.com/app_dev.php/`

You should be able to login with one of the generated users.

### Testing

Two types of testing are implemented. Unit and Functional. Unit testing was only used for the core functionality of the socket process. This is because 99% of the code is based on 3rd party libraries that are unit tested independently. So doing full unit test coverage is both redundant and a waste of energy.

Functional testing is used to test the client html interface. This effectively does everything the unit testing does and more.

#### Unit Testing

Run the following command to run all unit tests:

`./vendor/phpunit/phpunit/phpunit -c app`

This should be entirely self contained and work once the environment is functional. The tests are located here:

`src/AppBundle/Tests/`

#### Functional Testing

Functional testing is quite a bit more involved. It will require configuring a javascript compatible test driver. Doing all of this is beyond the scope of this document.

Selenium must be running and can be started with `java -jar src/AppBundle/Resources/private/jar/selenium-server-standalone-2.44.0.jar`

To run the tests execute:

`./bin/behat`

If everything is configured correctly, a chrome browser should pop-up, test the site and results should show at the prompt. The feature files are located here:

`features`

### Api Docs

To make this easier, once you have the site working you can visit `http://chatserver.allofzero.com/app_dev.php/doc/` to see the restful API.

### Debug

The easiest place to debug problems is in the example HTML client. Debugging output will happen on any symfony environment that is configured for debug. By default only the dev environment has debugging enabled.

For example if you point your url's at a base app_dev.php, the javascript will include console.log info that is viewable in the browser debug inspector. In addition if the ioserver is launched in the dev environment it will output debug info at the prompt.

### Deploy

* configure app/config/deploy.rb for your environment (it comes pre-built for the ones above deployed to a different location on the current machine) 
* run `cap deploy:setup` to get your target server ready (should only need to be done once)
* run `cap deploy` to push your app to the servers. It will restart the socket server for you.

These tasks are highly tuned toward the target server. They will need adjusting to deploy to a different distribution or environment.

### Summary

