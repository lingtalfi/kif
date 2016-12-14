The init file
=================
2016-12-14





Table of contents
--------------------

- [The init file](#the-init-file)
  * [Entry points](#entry-points)
- [Sections](#sections)
  * [the autoloader section](#the-autoloader-section)
  * [the functions section](#the-functions-section)
  * [the local vs prod section](#the-local-vs-prod-section)
  * [the php section](#the-php-section)
  * [the redirection section](#the-redirection-section)
  * [the database section](#the-database-section)
  * [the general config section](#the-general-config-section)
- [Example one: a simple init.php file](#example-one--a-simple-initphp-file)
- [Example two: a complex init.php file](#example-two--a-complex-initphp-file)






The **init.php** file contains the main configuration of your application.

It initializes the application: it's the entry point of your web application.

This means it must be included by your application every time your application is requested.

This also means that the **init.php** contains only the configuration that is common to all your pages,
because we want our application to be fast.

Page specific configuration is configured elsewhere, as we will see in a later chapter.


Entry points
-----------------
The (web) entry points of a kif application are the following: 

- **www/index.php** 
- any php file inside the "services" directory, if your application requires one (generally **www/services/**) 


Sections
===============

The init file is organized in "sections".

Each section configures a given area of your application.

The sections are:

- autoloader
- functions
- local vs prod
    - database parameters
    - display_errors
- php
- redirection
- database
- general config (constants)




The first thing to do when you create a new kif application is to review all your configuration, section by section,
removing section you won't need. 

The configuration process is generally done once per application.

The given sections are just a generic starting point, and you can add new sections if your application needs 
them, but remember: you should only create a section if it is used by all of your pages (90%+ is ok too..., you decide).

That's because the init file will be called every time your web application is requested, so the lighter the better.


the autoloader section
--------------
2016-12-14

The goal of this section is to autoload your classes.

Kif uses the [universe autoloader](https://github.com/karayabin/universe-snapshot), which makes it easy to organize your classes.

Note that you can use composer in parallel (for instance if you want to use SwiftMailer to send mails),
in this case, you would need to uncomment the last line.


```php
//------------------------------------------------------------------------------/
// UNIVERSE AUTOLOADER (bigbang)
//------------------------------------------------------------------------------/
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/BeeAutoloader.php';
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/ButineurAutoloader.php';
ButineurAutoloader::getInst()
    ->addLocation(__DIR__ . "/class")
    //->addLocation(__DIR__ . "/class-modules")
    ->addLocation(__DIR__ . "/class-planets");
ButineurAutoloader::getInst()->start();

// require_once __DIR__ . '/vendor/autoload.php'; // if you use composer
 
```


the functions section
-----------------------
2016-12-14

This sections imports your application's php functions definitions.

I like to define the translator **__** (double underscore) function in this file,
and also an **url** function that helps having consistent url paths.


```php
//--------------------------------------------
// FUNCTIONS
//--------------------------------------------
require_once __DIR__ . "/functions/main-functions.php";

```




the local vs prod section
-----------------------
2016-12-14


This sections is special.

It differentiates between the application configuration on your local machine and the application configuration
on your server machine.

Basically, it's a switch between the dev and prod environments.

The goal of this section is to allow you deploy your app on the prod server without having to change anything.

Of course, it's only adapted if you use a local and a prod environments. 



```php
//--------------------------------------------
// LOCAL VS PROD
//--------------------------------------------
if (true === Helper::isLocal()) {
    // php
    ini_set('display_errors', 1);

    // db
    $dbUser = 'root';
    $dbPass = 'root';
    $dbName = 'oui';

    // privilege
    $privilegeSessionTimeout = null; // unlimited session
} else {
    // php
    ini_set('display_errors', 0);

    // db
    $dbUser = 'root';
    $dbPass = 'root';
    $dbName = 'oui';

    // privilege
    $privilegeSessionTimeout = 60 * 5;
}

```

In the example above, the variables defined (dbUser, dbPass, ...) are used by other sections in the same **init.php** file.

The **Helper::isLocal** object::method is specific to your application.

Therefore you are required to create a Helper class in **class/Helper.php**.

Here is the one I use (my local machine is MacOsX and my prod server is a linux):



```php
<?php

class Helper
{

    public static function isLocal()
    {
        if ('/Volumes/' === substr(__DIR__, 0, 9)) {
            return true;
        }
        return false;
    }
    
    // other helper methods specific to this kif application...
}

```


the php section
-----------------------
2016-12-14

The php section contains the php directives in your app.

I'm giving two examples below (so that you understand that yours could be completely different): the first one is the 
simplest, the second one uses sessions.



```php
//--------------------------------------------
// PHP
//--------------------------------------------
date_default_timezone_set('Europe/Paris');
ini_set('error_log', __DIR__ . "/log/php.err.log");


```


```php
//--------------------------------------------
// PHP
//--------------------------------------------
date_default_timezone_set('Europe/Paris');
ini_set('error_log', __DIR__ . "/log/php.err.log");
if (null !== $privilegeSessionTimeout) { // or session expires when browser quits
    ini_set('session.cookie_lifetime', $privilegeSessionTimeout);
}
else{
    ini_set('session.cookie_lifetime', 10 * 12 * 31 * 86400); // ~10 years
}
session_start();

```



the redirection section
-----------------------
2016-12-14

This sections is basically a workaround for a situation that I had.

When you use write asset urls in an html page, you might use the absolute notation, which starts with a 
slash (for instance /styles.css).
 
However, this absolute reference is synced to the webserver's "host name",
which means basically that:

- if your application is **http://mysite/**, then the asset called will be **http://mysite/styles.css**
- however if your application is **http://123.456.78.90/mysite**, then the asset called will be **http://123.456.78.90/styles.css**
    - and the asset won't be found, because you need to call **http://123.456.78.90/mysite/styles.css**
 

So when I realized that, I was in a hurry and I created the redirection section (below), which creates an **URL_PREFIX** constant
used by a **url** custom function, which basically ensures that the asset you are calling will be synced with your host name.

This is an ugly fix, not proud of it, might be moved somewhere else in the future, but for now it does the job.



```php
//--------------------------------------------
// REDIRECTION
//--------------------------------------------
if ('/index.php' === $_SERVER['PHP_SELF']) {
    define('URL_PREFIX', '');
} else {

    define('URL_PREFIX', substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
}
```


Although this is an ugly section, it's a good example that demonstrates how you own the init.php file
and can bend it to your needs.



Remember: the only thing to keep in mind when creating a new section in the **init.php** file is that this section
will be loaded on every request.





the database section
-----------------------
2016-12-14

This sections initializes the database.

I put it here because I figured that generally, if an application uses a database, it uses it on every page.

If this is not the case, then maybe you should remove this section and use a more appropriate lazy 
service (please refer to the kif documentation for more details on how to do that).


```php
QuickPdo::setConnection("mysql:host=localhost;dbname=$dbName", $dbUser, $dbPass, [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')), NAMES 'utf8'",
//    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES','')), NAMES 'utf8'",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
```


Note that I'm using [QuickPdo](https://github.com/lingtalfi/QuickPdo) here,
but you probably can use any system that you like. 




the general config section
-----------------------
2016-12-14

This sections defines the "constants" use application wide.

In kif, we define two types of constants:

- the php constants, immutable
- the application "constants", mutable


Again, you don't define all the constants of all your modules here, that would be a total waste of cpu.

Instead, you just define the constants that are common to all your pages.


```php

//--------------------------------------------
// GENERAL CONFIG
//--------------------------------------------
// paths
define('APP_ROOT_DIR', __DIR__);

// website
// used in mail communication and authentication form,
// used in html title, and at the top of the left menu
define('WEBSITE_NAME', 'My Website');


Spirit::set('ricSeparator', '--*--');

```


The **APP_ROOT_DIR** constant is the most important one, it basically allows any application class to target any file
in the system.

The **WEBSITE_NAME** is an important constant with multiple uses:
 
- it can serve as the default html page title
- it serves in various places inside the html page body 
- it can be used in the title of a mail message
- and probably more?
 
 
The **Spirit** object (class/Spirit.php) is an object that you have to create yourself.

It represents the spirit of your application (please refer to the kif documentation for more info).

In this example (from [nullos admin](https://github.com/lingtalfi/nullos-admin)), it defines the ricSeparator application 
constant, which is used by the crud system of nullos admin.


The main difference between a php constant and a spirit constant (or application "constant") is that the spirit constant
is mutable, although unlikely to be changed.





Example one: a simple init.php file
=====================================
2016-12-14


I used this **init.php** for a local testing app (based on [the beauty and the beast pattern](https://github.com/lingtalfi/Dreamer/blob/master/UnitTesting/BeautyNBeast/pattern.beautyNBeast.eng.md)). 


```php
<?php

use BumbleBee\Autoload\ButineurAutoloader;


//------------------------------------------------------------------------------/
// UNIVERSE AUTOLOADER (bigbang)
//------------------------------------------------------------------------------/
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/BeeAutoloader.php';
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/ButineurAutoloader.php';
ButineurAutoloader::getInst()
    ->addLocation(__DIR__ . "/class")
    ->addLocation(__DIR__ . "/class-planets");
ButineurAutoloader::getInst()->start();


//--------------------------------------------
// FUNCTIONS
//--------------------------------------------
//require_once __DIR__ . "/functions/main-functions.php";


//--------------------------------------------
// LOCAL VS PROD
//--------------------------------------------
if ('local') {
    // php
    ini_set('display_errors', 1);

}


//--------------------------------------------
// PHP
//--------------------------------------------
date_default_timezone_set('Europe/Paris');
ini_set('error_log', __DIR__ . "/log/php.err.log");



//--------------------------------------------
// REDIRECTION
//--------------------------------------------
if ('/index.php' === $_SERVER['PHP_SELF']) {
    define('URL_PREFIX', '');
} else {

    define('URL_PREFIX', substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
}



//--------------------------------------------
// GENERAL CONFIG
//--------------------------------------------
// paths
define('APP_ROOT_DIR', __DIR__);
define('WEBSITE_NAME', 'Btests');


```


Notice that since this project is local only, I don't need to bother with the "local vs prod" section.





Example two: a complex init.php file
=====================================
2016-12-14

I used the following **init.php** file for the [nullos admin](https://github.com/lingtalfi/nullos-admin) project.



```php
<?php

use BumbleBee\Autoload\ButineurAutoloader;
use Crud\CrudModule;
use Lang\LangModule;
use Privilege\Privilege;
use Privilege\PrivilegeUser;
use QuickPdo\QuickPdo;


//------------------------------------------------------------------------------/
// UNIVERSE AUTOLOADER (bigbang)
//------------------------------------------------------------------------------/
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/BeeAutoloader.php';
require_once __DIR__ . '/class-planets/BumbleBee/Autoload/ButineurAutoloader.php';
ButineurAutoloader::getInst()
    ->addLocation(__DIR__ . "/class")
    ->addLocation(__DIR__ . "/class-modules")
    ->addLocation(__DIR__ . "/class-planets");
ButineurAutoloader::getInst()->start();


//--------------------------------------------
// FUNCTIONS
//--------------------------------------------
require_once __DIR__ . "/functions/main-functions.php";


//--------------------------------------------
// LOCAL VS PROD
//--------------------------------------------
if (true === Helper::isLocal()) {
    // php
    ini_set('display_errors', 1);

    // db
    $dbUser = 'root';
    $dbPass = 'root';
    $dbName = 'oui';

    // privilege
    $privilegeSessionTimeout = null; // unlimited session
} else {
    // php
    ini_set('display_errors', 0);

    // db
    $dbUser = 'root';
    $dbPass = 'root';
    $dbName = 'oui';

    // privilege
    $privilegeSessionTimeout = 60 * 5;
}


//--------------------------------------------
// PHP
//--------------------------------------------
date_default_timezone_set('Europe/Paris');
ini_set('error_log', __DIR__ . "/log/php.err.log");
if (null !== $privilegeSessionTimeout) { // or session expires when browser quits
    ini_set('session.cookie_lifetime', $privilegeSessionTimeout);
}
else{
    ini_set('session.cookie_lifetime', 10 * 12 * 31 * 86400); // ~10 years
}
session_start();



//--------------------------------------------
// REDIRECTION
//--------------------------------------------
if ('/index.php' === $_SERVER['PHP_SELF']) {
    define('URL_PREFIX', '');
} else {

    define('URL_PREFIX', substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
}


//--------------------------------------------
// DATABASE CONNEXION
//--------------------------------------------
QuickPdo::setConnection("mysql:host=localhost;dbname=$dbName", $dbUser, $dbPass, [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')), NAMES 'utf8'",
//    PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES','')), NAMES 'utf8'",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);


//--------------------------------------------
// GENERAL CONFIG
//--------------------------------------------
// paths
define('APP_ROOT_DIR', __DIR__);


// website
// used in mail communication and authentication form,
// used in html title, and at the top of the left menu
define('WEBSITE_NAME', 'My Website');


Spirit::set('ricSeparator', '--*--');


//--------------------------------------------
// PRIVILEGE
//--------------------------------------------
PrivilegeUser::$sessionTimeout = 60 * 5 * 1000;
PrivilegeUser::refresh();
if (array_key_exists('disconnect', $_GET)) {
    PrivilegeUser::disconnect();
    if ('' !== $_SERVER['HTTP_REFERER']) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
Privilege::setProfiles([
    'root' => [
        '*',
    ],
    'admin' => [],
]);


//--------------------------------------------
// TRANSLATION
//--------------------------------------------
define('APP_DICTIONARY_PATH', APP_ROOT_DIR . "/lang/" . LangModule::getLang('en'));


```

Notice the two extra sections: privilege and translation.

The [privilege](https://github.com/lingtalfi/Privilege) system is used for every page in nullos.
 
 
Notice that I spent an extra if condition (if array_key_exists), which is not strictly required.

If I had more than 5 conditions in my init file, that would drive me crazy, but this is just one condition,
and it doesn't matter to me.

My point is that when you create an **init.php** file, you should always be aware of the consequences of what
you are putting in it.



