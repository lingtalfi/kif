<?php

use BumbleBee\Autoload\ButineurAutoloader;


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


// website
// used in mail communication and authentication form,
// used in html title, and at the top of the left menu
define('WEBSITE_NAME', 'My Website');

