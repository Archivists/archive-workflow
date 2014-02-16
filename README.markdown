#Digitization Workflow

This is a simple job tracking application for the transfer (digitization), of carriers - whether open reel tape, audio cassette, or video tapes.

Certain assumptions are made concerning archive identifiers or 'persistence identifiers' (PIDs). A job represents a carrier i.e. physical media.

More documentation and Wiki pages will follow.

The framework for this application is [Laravel 4](http://laravel.com/).

##Requirements

	PHP >= 5.4.0 (Entrust requires 5.4, this is an increase over Laravel's 5.3.7 requirement)
	MCrypt PHP Extension

##Installation

### Step 1: Download the application source

Either clone this git repository...

	git clone git@github.com:Archivists/workflow.git myproject

Or download the master branch archive zip file.

	https://github.com/Archivists/workflow/archive/master.zip

### Step 2: Install dependencies

Laravel (and this application) relies on the [Composer Dependency Manager](http://getcomposer.org/). Read composer instructions for local or global installation instructions.

#### Option 1: Composer is not installed globally

    cd workflow
	curl -s http://getcomposer.org/installer | php
	php composer.phar install --dev

#### Option 2: Composer is installed globally

    cd workflow
	composer install --dev

If you are installing to a test or development machine first (recommended), note the use of the `--dev` flag.

Some packages used to preprocess and minify assests are required on the development environment. The local environment will also generate a ***composer.lock*** file. Be sure to include this when you deploy to production and only run `php composer.phar install` (without the --dev option) on the production server.

If you are deploying directly from this repository to a production environment, run `php composer.phar install` (without the --dev option and without the lock file).

### Step 3: Configure 

Laravel 4 will load configuration files depending on your environment.

NOTE: /bootstrap/start.php has been excluded from this repository. See Step 9: below. You will need to create this file first. It can be copied from a local default laravel install, or by pasting the following text into a file named start.php and saving this file into the bootstrap directory.

    <?php

    /*
    |--------------------------------------------------------------------------
    | Create The Application
    |--------------------------------------------------------------------------
    |
    | The first thing we will do is create a new Laravel application instance
    | which serves as the "glue" for all the components of Laravel, and is
    | the IoC container for the system binding all of the various parts.
    |
    */

    $app = new Illuminate\Foundation\Application;

    /*
    |--------------------------------------------------------------------------
    | Detect The Application Environment
    |--------------------------------------------------------------------------
    |
    | Laravel takes a dead simple approach to your application environments
    | so you can just specify a machine name or HTTP host that matches a
    | given environment, then we will automatically detect it for you.
    |
    */

    $env = $app->detectEnvironment(array(

        'local' => array('your-local-machine-name'),
        'staging' => array('your-staging-machine-name'),
        'production' => array('your-production-machine-name'),

    ));

    /*
    |--------------------------------------------------------------------------
    | Bind Paths
    |--------------------------------------------------------------------------
    |
    | Here we are binding the paths configured in paths.php to the app. You
    | should not be changing these here. If you need to change these you
    | may do so within the paths.php file and they will be bound here.
    |
    */

    $app->bindInstallPaths(require __DIR__.'/paths.php');

    /*
    |--------------------------------------------------------------------------
    | Load The Application
    |--------------------------------------------------------------------------
    |
    | Here we will load the Illuminate application. We'll keep this is in a
    | separate location so we can isolate the creation of an application
    | from the actual running of the application with a given request.
    |
    */

    $framework = $app['path.base'].'/vendor/laravel/framework/src';

    require $framework.'/Illuminate/Foundation/start.php';

    /*
    |--------------------------------------------------------------------------
    | Return The Application
    |--------------------------------------------------------------------------
    |
    | This script returns the application instance. The instance is given to
    | the calling script so we can separate the building of the instances
    | from the actual running of the application and sending responses.
    |
    */

    return $app;


Open ***bootstrap/start.php*** and edit the following lines to match your settings. Configure your machine name. In Windows check the host name under 'Computer -> Propertires'. In OS X and Linux type `hostname` in terminal. Using the machine name will allow the `php artisan` command to use the right configuration files as well.

    $env = $app->detectEnvironment(array(

        'local' => array('your-local-machine-name'),
        'staging' => array('your-staging-machine-name'),
        'production' => array('your-production-machine-name'),

    ));

A local folder has already been created in ***app/config/local*** corresponding to your local environment if you are going to test or develop before deployment.

You can created seperate ***app/config/staging*** and ***app/config/production*** folders for these respective environments. Once the folder has been created, copy configuration files into the directory and change settings to suit the environment (local, staging, production etc.).

For example, the ***app/config/app.php*** has already been copied into the ***app/config/local*** directory and will look something like this... (Note: 'key' should be changed for each environment. See Step 7 below.)

    <?php

    return array(

        'url' => 'http://localhost',

        'timezone' => 'UTC',

        'key' => 'YourSecretKey!!!',

        'providers' => array(
        
        [... Removed ...]
        
        ),

    );


### Step 4: Configure Database

The database configuration is also environment specific - and a sample database file has been placed in in ***app/config/local/database.php***.

You'll need to create a matching database and user account for the database management system in use. The current ***app/config/local/database.php*** file expects a MySQL database connection with a database named: archworkflow_www, user: archworkflow and password: test. Update these to suit your own installation.

Here's an example script for MySQL.

	mysql -u root -proot_password
	mysql> CREATE USER "archworkflow"@"localhost" IDENTIFIED BY "password";
	mysql> CREATE USER "archworkflow"@"%" IDENTIFIED BY "password";
	mysql> CREATE DATABASE archworkflow_www;
	mysql> GRANT ALL PRIVILEGES ON archworkflow_www.* TO "archworkflow"@"localhost" IDENTIFIED BY "password";
	mysql> GRANT ALL PRIVILEGES ON archworkflow_www.* TO "archworkflow"@"%" IDENTIFIED BY "password";
	mysql> FLUSH PRIVILEGES;
	mysql> SHOW DATABASES;

### Step 5: Configure Mailer

Mailer settings can also be set by environment and an example file has already been placed in the ***app/config/local/mail.php***. 

The default mailer setting for the local configuration is `'pretend' => true,` - which will allow you to run the application on a local or test machine without mailer settings.

### Step 6: Populate Database
Run these commands to create and populate the application tables:

	php artisan migrate
	php artisan db:seed

Once the migrations are run and you've seeded the database -  Admin:admin, User:user accounts will be created as well as default permissions.

### Step 7: Set Encryption Key

A unique encryption key should be set for all environments.

***In app/config/app.php***

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, long string, otherwise these encrypted values will not
    | be safe. Make sure to change it before deploying any application!
    |
    */

	'key' => 'YourSecretKey!!!',

You can use artisan to do this

    php artisan key:generate

By default, the new key will be generated in ***app/config/app.php***. You'll need to copy the key setting over to your ***app/config/local/app.php***, or ***app/config/staging/app.php***, or ***app/config/production/app.php*** configuration file.

### Step 8: Make sure app/storage is writable by your web server.

If permissions are set correctly:

    chmod -R 775 app/storage

Should work, if not try

    chmod -R 777 app/storage



### Step 9: Application Specific Configuration.

NOTE: /app/config/staging, app/config/production directories and /bootstrap/start.php are all excluded from the repository. You would need to create or copy these into your installation, in particular setting the hostnames for the "Detect The Application Environment" settings. e.g.:

    $env = $app->detectEnvironment(array(

        'local' => array('your-local-machine-name'),
        'staging' => array('your-staging-machine-name'),
        'production' => array('your-production-machine-name'),

    ));

Two writeable directories are required for the application. A repository path (likely a network share in production) and a DAW pickup directory.

The repository path will be used to store supporting artefacts for the carrier, including box cover photos, transcripts, photos of the carrier itself, scanned documents etc.

The repository path needs to be set in ***app/config/workflow.php*** (or the respective environment folder - local, staging, production etc.).

The DAW pickup folder is the location from which the digital audio workstation will pickup an XML manifest for this job - including archive idendifier (or PID).

An example configuration setting can be found in ***app/config/workflow.php*** and ***app/config/local/workflow.php***:

    /*
    |--------------------------------------------------------------------------
    | Application Specific Settings
    |--------------------------------------------------------------------------
    |
    | Settings here are specific to the digitization workflow application, 
    | incuding repository location, digitial audio workstation pick-up location
    | etc.
    |
    */

    'repository'  => '/Users/tony/Projects/Archivists/Data/repository/',
    'daw_pickup'   => '/Users/tony/Projects/Archivists/Data/daw_pickup/',
    
    'library'     => 'gd',
    'upload_dir'  => 'uploads',
    'upload_path' => public_path() . '/uploads/',
    'quality'     => 85,
    'dimensions' => array(
        'thumb'  => array('thumbnails', 100, true,  80),
        'medium' => array('previews', 800, false, 90),
    ),


### Production Launch

By default debugging is enabled. Before you go to production you should disable debugging in `app/config/app.php`

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => false,

You can use the local PHP server to run the application locally. NOTE: On last attempt - uploading artifacts to the built-in PHP server did not work, and so to test uploads, you'll need to configure a local webserver (Apache or IIS).

`php artisan serve --port=4000`

##Roadmap

* Documentation.
* Clean up validators - skinny controllers, fat models.
* Complete localization.
* Move all file i/o tasks into a queue for async processing.
* Include QUADRIGA XML import sample action on status change to 'digitization'.

## License

GNU AFFERO GENERAL PUBLIC LICENSE

