#Chat Server
Chat server is a proof of concept real time communication server written in php

### System Requirements

The basic requirements are outlined below. Making a compatible server is beyond the scope of this guide.

#### Hosting

* PHP >= 5.4 < 5.6
* Symfony >= 2.6-Beta
* ZeroMQ system library (for real-time socket based messages)
* ZeroMQ php library (installable via pecl)
* MySQL or Sqlite for DB (depends on the environment)
* PHP composer

#### Dev/Deployment

* Ruby (tested with 2.1+)
* Capistrano (Ruby gem)
* Capifony (Ruby gem for capistrano)
* capistrano_rsync_with_remote_cache (Ruby gem for rsync copies)
 
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
* Setup apache or use the php built-in server
* Copy app/config/parameters.yml.dist to app/config/parameters.yml
* Edit app/config/parameters.yml for your db systems

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

`./app/console chat:server 8080 5555`

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

This should be entirely self contained and work once the environment is functional.

#### Functional Testing

Functional testing is quite a bit more involved. It will require configuring a javascript compatible test driver. Doing all of this is beyond the scope of this document.

### Deploy

* configure app/config/deploy.rb for your environment (it comes pre-built for the ones above) 
* run `cap deploy:setup` to get your target server ready (should only need to be done once)
* run `cap deploy` to push your app to the servers
