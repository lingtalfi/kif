Kif
==============
2016-12-14



Komin> presents: KIF (Komin> Intuitive Framework).


Kif is a framework I use when creating web applications (any).

It's intuitive, simple and straightforward.



Kif is written in php.
 
 


Summary
===========
- [Philosophy](#philosophy)
- [The project tree structure](#the-project-tree-structure)
  * [Extension](#extension)
- [The app tree structure](#the-app-tree-structure)
  * [init.php](#initphp)
  * [class/](#class-)
  * [class-modules/](#class-modules-)
  * [class-planets/](#class-planets-)
  * [class-whatever/](#class-whatever-)
  * [functions/](#functions-)
  * [log/](#log-)
  * [pages/](#pages-)
  * [scripts/](#scripts-)
  * [vendor/](#vendor-)
  * [www/](#www-)
- [index.php](#indexphp)
- [Exception handling strategy](#exception-handling-strategy)
  * [Fallback strategy](#fallback-strategy)
  * [Localized strategy](#localized-strategy)
- [Multi-language](#multi-language)
  * [The lang directory](#the-lang-directory)
  * [The __ function](#the----function)
- [Spirit](#spirit)
- [Basic kif concepts](#basic-kif-concepts)
- [Nomenclature](#nomenclature)
  * [Bridge](#bridge)
  * [Module](#module)
  * [Class naming conventions](#class-naming-conventions)
- [Private TODO:](#private-todo-)
- [Version history](#version-history)








Philosophy
===============
2016-12-14


I like when things are intuitive and straightforward.

Intuitive means two things:

- things are where you expect them
- you can guess where things are without reading this doc, just using common sense



Straightforward means there is almost no extra "organizational" layer in between the task your application wants to do
and the code in the app. In other words, the code executes faster.
 
 
By "organizational", I mean a layer that enhances the comfort of the developer, often without consideration of the efficiency 
of the application.





The project tree structure
=====================
2016-12-14


- app/


As you can see, the project structure is very simple.

The **app/** directory contains all the files related to the web application.

It's the directory that you will upload to a web server, and therefore should contain only the files representing
your app.


Extension
-------------
If you have other directories like photoshop sources for instance you can create a folder next to the app, so that
all the files related to your project are in one place.

I like to have my database schema in my project directory, and before I switched to a different system,
I used to put my client's invoices in here too. 

So if you are a "per-project" type of person, you can put everything related to your project in this directory.






The app tree structure
==========================
2016-12-14 
 

The **app/** directory represents the web application.


- class/ 
- ?class-modules/
- class-planets/
- ?class-whatever/
- functions/
- log/       (writable)
- pages/
- ?scripts/
- ?vendor/  (if you use composer)
- www/
- init.php




init.php
--------------

The init file is a big topic.

Therefore, it has been moved to the dedicated [init file page](https://github.com/lingtalfi/kif/tree/master/doc/init-file.md).




class/
-----------
2016-12-14


This directory contains your application specific classes (aka **core classes**).

You can create your own classes in this directory.

Kif by default uses the [bsr0](https://github.com/lingtalfi/BumbleBee/blob/master/Autoload/convention.bsr0.eng.md) naming system 
for classes namespaces (a simplified variation of the [psr-0](http://www.php-fig.org/psr/psr-0/) naming system).


This can be changed from the **init.php** file, from the "autoloader" section.

The following classes are generally used in a kif application:

- Helper: application level helper
    - it contains the isLocal method used in the "local vs prod" section of the **init.php** file (if you use this section of course)
- Spirit: the spirit of the application
    - it contains the most common application "constants"
- Logger: the logger bridge of the application
    - note: the naming of this class is exceptional: it should be named LoggerBridge instead (see "class naming conventions" 
    term in the "nomenclature" section), but since it's a widespread object, it has been named "Logger" for short.
- Router/RouterBridge: 
    - you could need this class if your application was modular (i.e. if it uses modules 
    to incorporate/organize functionalities)        
    - this is just an example that I wanted you to be aware of, the point is: if you need a non default router system (see the "basic kif concepts" section), it's specific to
        your application (and therefore should be in the **/class** directory)

- Layout/:
    - a directory where you can put your layout helpers (if you use them)

- any other class that belongs to your application




class-modules/
-----------
2016-12-14

If you are creating a modular application (i.e. an application that uses modules),
then you can create a **class-modules** directory which contains all your modules.

By default, a kif application uses a modular architecture, and uses the following modules:
 
- ApplicationLog
    - this module is hooked to the Logger bridge (**class/Logger.php**) by default
    - this module writes application errors to the **log/app.log** file
- Lang
    - this module is responsible for knowing which languages are allowed, and which one is the current lang
    - this module is also responsible for displaying html elements specific to your application, 
            like a top bar with a language selector for instance



class-planets/
-----------
2016-12-14

The **class-planets/** directory represents the planets directory from the universe framework.
If you don't use the universe framework, you don't need this directory.

However, the **class-planets/** directory is there by default in a kif application because
the default autoloader of a kif application comes from the universe framework.



class-whatever/
-----------
2016-12-14


You may have noticed that I've started using a naming pattern here for organizing the classes:
using the "class-" prefix.

If you like it, you can use the **class-whatever/** directory to put your the "whatever" things
that your application has (**class-plugins/** for all your plugins for instance).

Of course if you don't like this convention, or if you have other constraints, you can organize
your classes exactly how you want them.

This is done inside the "autoloader" section of your **init** file.


The only classes that are not using this convention in a native kif application are the
classes inside the **vendor** directory (if you use composer), which is the directory for composer.




functions/
-----------
2016-12-14


This is the directory for functions, should you use some functions.

Although kif is object oriented, using functions have some benefits too:

- functions are slightly faster to call
- functions are easier to invoke than object' methods

Of course, using a **functions/** directory is just the default in kif, and you can put your functions in any location
you want, should you want to.




log/
-----------
2016-12-14


The **log/** directory is the default directory reserved for log files.

There are two log files:

- php.log: the php log file
- app.log: the application log file


Again, you can change those paths to your likings if needed.

The php log file's location is defined in the "php" section of the **init.php** file (the error_log directive).

The application log file is defined inside the Logger (bridge) object (**class/Logger.php**).







pages/
-----------
2016-12-14


The **pages/** directory contains the kif **pages**.

A page can return either:

- a http response (301, 404, etc...)
- a visual html page (with images, javascript, etc...)


Since those are common jobs, kif provides some helpers:

- for http responses, <-todo-> you can use the HttpResponse core helpers
- for rendering visual html pages, you can use the default Layout object provided in every kif application as a starting point
        for creating your own layouts (see the "Basic kif concepts" section for more details).
 
 

scripts/
-----------
2016-12-14


I don't know about you, but I love scripts.

They can be a huge time saver.

If you like scripts and you are on mac, maybe you would be interested in some management
tools like the [task manager](https://github.com/lingtalfi/task-manager), which makes
the calling of all your scripts a breeze.

Anyway, the **scripts/** directory is where your scripts are located.

You can create scripts in the language you like (bash, php, others...).









vendor/
-----------
2016-12-14


The **vendor/** directory is reserved for [composer](https://getcomposer.org/) imported classes.





www/
-----------
2016-12-14


The **www/** directory is the public directory served by your webserver.

It contains all your assets, and the **www/index.php** file.






index.php
=================
2016-12-14


The **www/index.php** file is the main entry point of your web application.

By default, all http requests will be handled by the **www/index.php** file.

The only http requests not handled by the **www/index.php** file are those who request **services** (if you create services).


The **www/index.php** file is very important, and, like the **init.php** file, it should be customized 
(or at least reviewed once) to your application needs.



Although you can customize the **www/index.php** file a lot, the general organization of the **www/index.php** page
is always the same.

- initialize the application (include the **init.php** file)
- try
    - router: find the **kif page** to call
    - call the **kif page**
- catch
    - call the **exception.php** page
- page not found? call 911, I mean **404.php**    



On top of that, you can add extra features such as privilege checking (and redirect to a login page if the
user is not connected) or other routing level systems if your application needs them.
 
 
As it has been said earlier, kif philosophy is to start little, and grow only if necessary.
 
The **www/index.php** file contains the default kif router which is a simple php array.

As long as your web application doesn't need more, this router is fine, efficient, and easy to use.
 
If you need to create a new route, simply add an entry to the router array (the $uri2pageMap variable inside
the **www/index.php** file).


The default **www/index.php** file also comes with an exception catcher block, just in case.

You can get rid of it if you think it's obsolete, but it is basically the last wall before 
your user gets an "in your face exception".

When such a dramatic exception is caught, it calls the **exception.php** page by default.

If you are interested in "exception handling" strategies in kif, please check the "exception handling strategy" section. 


   
    

So that's it: the **www/index.php** basically executes your application from the beginning to the end: it starts
by analyzing the url and choosing the appropriate page, and then it calls the page which renders the appropriate
http response.
If an exception was uncaught (which is not supposed to happen), it calls the exception page. 









Exception handling strategy
========================
2016-12-14


There are two main strategies for handling exceptions in kif:

- fallback strategy
- localized strategy


Fallback strategy
--------------------

I have my own metaphor about exception handling.

An exception is like a frisbee thrown potentially from anywhere in your application, and towards your user's face.
 
If your application doesn't catch it, your front user will get it in her face.

To avoid this embarrassing situation, kif provides a default exception catcher in the **www/index.php** file,
which basically is your last rampart before your user get the exception in her face.

It's not supposed to be used, but it's there, just in case (because we all know how 
developers are particularly talented at creating weird unexpected bugs).

When the catcher catches this exception, it calls the **pages/exception.php** page (by default, or change it form inside
**www/index.php**), so that you can do whatever you want with it.


That's the first strategy of handling exception, but there is another one.



Localized strategy
--------------------

The second one is less dramatic and more localized.

It's the idea that your layout catches exception coming 
from layout elements (which is probably where most exceptions will come from).

The benefit of this is that the exception is localized to a particular area of your html page, 
and therefore, it looks like it's more controlled.

Typically, an exception will occur inside the "body" layout element of your layout (if you have a "body" layout element),
and by catching it you will be able to render all the website (top bar, left column, ...) except your "body" which will
display an "oops an error occurred..." message.

That's arguably a better solution that a generic "Oops an error occurred" message.


I generally implement this localized strategy in my personal projects, but I did not want to impose it to the kif framework.

(In fact, I almost removed the fallback strategy as well, but eventually I thought that it was easier to remove than to add,
so I let it in place.)









 

Multi-language
==================
2016-12-14


The good news is that kif was designed to be multi-language from the beginning.

There is no obligation to use my system, but here is my system, which is the recommendation
if you want to implement the multi-language in kif.


The lang directory
---------------------


There is a **lang/** directory which contains all the languages.
For instance if you are french, all your translations should be in the **lang/fr/** directory.

Inside the **lang/fr/** directory, you create php files.

You can create subdirectories for organizational purposes (for instance one for **/lang/fr/modules**).

Every file and/or directory that exist in a lang should exist in the others (that your application uses of course).

This means the **/lang/fr/** and **/lang/en/** directories should look like clones from a tree perspective.

The only thing that changes between two languages is the translations, which occurs in the php files (called translation files).


A translation file name is important: it represents the context.

So for instance, a file named **default.php** contains the translations for the "default" context, and
a file named **modules/authentication/authentication-form.php** contains the translation for
the "modules/authentication/authentication-form" context.


(The context is basically the second argument of the __ function.)

The php file contains one and only one variable: $defs.

$defs is an array which keys are called the identifiers (aka messages), and which values are the translations.

Identifiers are the same across the languages, only the translations change.

Here is the content of an example translation file:
 
```php
<?php


$defs=[
    'Welcome to {website}' => "Welcome to {website}",
    //--------------------------------------------
    // KOOL
    //--------------------------------------------
    'Log In' => "Log In",
    'This credentials are invalid' => "This credentials are invalid",
    'Pseudo' => "Pseudo",
    'Password' => "Password",
];
```
 
As you can see, I used a kool section (just for the purpose of this example).

Sections are organizational tools which help the author keeping well organized messages.
 
Also notice the use of tags (i.e. {website}).

Tags are placeholders for a dynamic value and therefore should be the same 
across all languages (i.e. don't translate them).


The above example is in english, that's why the identifiers looks like the translations.

Finally, the identifiers can be very short (i.e. they don't have to be the english version of the translation).

This is useful if you have a very long text to translate:

- easier to invoke
- less memory consumed 


As I said, you are not forced to use this system if you don't want to.
For instance, if you are a big file of xml, you can implement your own xml system.

I use php files because I believe they are the fastest to process (as I just need to require them,
and they are instantly in memory).




The __ function
-----------------

Once your **lang/** directory is ready, you need a function to call them.

I like to use the __ (double underscore) function.

Again, do as you wish, but I use the __ function because:

- it's fast to invoke



This function is defined by default inside the **functions/main-functions.php** file.


It looks like this:


```php
function __($identifier, $context = 'default', array $tags = [])
{
    static $terms = [];


    // load definitions for the given context
    if (array_key_exists($context, $terms)) {
        $defs = $terms[$context];
    } else {
        $defs = [];
        $file = APP_DICTIONARY_PATH . '/' . $context . '.php';
        if (false === file_exists($file)) {
            throw new \Exception("translation file not found: " . $file);
        }
        require_once $file;
        $terms[$context] = $defs;
    }


    // use the loaded definitions and check if there is a matching identifier
    if (array_key_exists($identifier, $defs)) {
        $value = $defs[$identifier];
        if (count($tags) > 0) {
            $ks = array_map(function ($v) {
                return '{' . $v . '}';
            }, array_keys($tags));
            $vs = array_values($tags);
            $value = str_replace($ks, $vs, $value);
        }
        return $value;
    } else {
        // error?
        throw new \Exception("__ error: dictionary term not found: " . $identifier);
//                return $identifier;
    }
}

function ___()
{
    return htmlspecialchars(call_user_func_array('__', func_get_args()));
}
```


Notice that there is a ___ (triple undescore) function that basically adds a htmlspecialchars
filter to the __ (double underscore) function. This is useful for writing translations inside html attributes.


As you can see, the function's signature is quite straightforward:

- identifier
- context
- tags

Nothing new here.

Drop the tags curly braces when passing them to the __ function.

There are two more things that should grab your attention:

- the function uses a **APP_DICTIONARY_PATH** constant
- the function throws an exception if the identifier's translation is not found


The **APP_DICTIONARY_PATH** constant has to be defined somewhere.

It's defined in the **init.php** file, since all your website will probably use it.

There is an example of how it's implemented at the bottom of the "Example two: a complex init.php file"
section of the init file documentation.

I put it here again for your convenience.


```php
//--------------------------------------------
// TRANSLATION
//--------------------------------------------
define('APP_DICTIONARY_PATH', APP_ROOT_DIR . "/lang/" . LangModule::getLang('en'));
```

Notice that a LangModule is used to hold the information of the current lang.



About the Exception thrown, this is because I like it to have an exception thrown in my face
when any translation is missing. 

This forces me to create all the translations.
 
If you don't like this behaviour, just comment the statement.
















Spirit
==================
2016-12-14


The Spirit object represents the spirit of your application.


This class is basically a registry that contains general purpose variables to transmit between
the different objects of your applications.


Php constants can also do the job, but the problem with them is that they are immutable.

Class constants could also do the job and you can use them instead if you want (but again, they are immutable).

On the other hand, Spirit transits variables (i.e. you can change the state of the spirit variable).


If the constants define the static state of your application, then the Spirit object 
handles the dynamic state/configuration of your application (hence the term Spirit, which sounds more volatile).



So far, the following variables are set in a default kif application:

- uri: the current uri
    - this variable is set by the default router when a route matches







Basic kif concepts
====================
2016-12-14


What makes kif simple and intuitive is (amongst other things) that:

- it follows the natural rendering process of a page
- it starts with the minimum set of features needed and provides your with more functionality only when required


 
The natural rendering process of a page itself is simple:

- the user requests your application by typing an url
- your application analyzes the url and chooses which **page** to call
- the **page** returns the appropriate http response (most of the time an html page)


Kif doesn't get more complicated than that, but it's important to understand those concepts
nonetheless.

There are two important concepts:

- the router
- the page (or kif page to avoid disambiguation)


In kif, the router is the system that is given an url, and decide which **page** to call.

A router can be very complex or very simple, depending on your application needs.

In kif, the default router is the simplest router possible, which is simply a php array
which makes a direct correlation between an url and a **page**.

But what's a page exactly?

In kif, a **page** is actually a php script responsible for returning the http response
expected by the user.
Most of the time, this response is a visual **html page**, but it could also be
a simple http response (like 301, 404, etc...).

So a **page** has a lot of responsibility.

It's important to not confound a **page** (or kif page) with an html page, which is
the visual result that the front user interacts with.

Using a page system allows us to decouple the url from the page rendered,
and makes kif a flexible framework to work with.

One concrete benefit of this is that we can have pretty urls if we want to.


Since most of the work for returning the appropriate http
response is delegated to the **page**, kif provides you with some page helpers,
which alleviate the load of work to do.

- http response: not implemented yet
    - an object that helps returning non visual html pages (http responses like 301, 404, ...)
- layout: an object that helps you creating a visual html page
    - the principle of the layout is to factorize visual elements that are common
            to all/many the pages of your web application











Nomenclature
================
2016-12-14

In this section, I describe terms that have a special meaning in kif.


Bridge
-----------
2016-12-14


A Bridge class is the place where objects are linked to each others.

In a bridge relation, there are two actors:

- service provider
- service user

The **service provider** provides a Bridge class, which exposes the available services
for this class.

**Service users** can use the provided services by attaching themselves 
to the desired services of the bridge class.

This is called "hooking" (i.e. a **service user** "hooks" itself to the bridge class's service).


In kif, the hooking process is done by the developer directly (or an automated tool that does the job).

Being able to see/manipulate the hookings directly is part of what makes kif an intuitive framework.


In some regards, a bridge is a service container.
 
This means you can implement a lazy loading logic and keep instances in memory if that's what you
are looking after.

The mechanism is quite simple and a possible implementation is this:

```php
<?php



class Bridge
{


    private static $instances = [];


    //--------------------------------------------
    // APPLICATION SERVICES
    //--------------------------------------------





    //--------------------------------------------
    // INVOKER
    //--------------------------------------------
    public static function get($serviceName){
        return self::getInstance($serviceName);    
    }
    //--------------------------------------------
    // INSTANCES PREPARATION
    //--------------------------------------------
    
    private static function getBob()
    {
        return new Bob();
    }



    //--------------------------------------------
    // PRIVATE
    //--------------------------------------------
    private static function getInstance($name)
    {
        if (!array_key_exists($name, self::$instances)) {
            self::$instances[$name] = call_user_func('Bridge::get' . $name);
        }
        return self::$instances[$name];

    }


}
```

The example above can be a model for a bridge which loads instances lazily (like a full featured service container).

In you use the class above, you create your instances, manually, in the "instances preparation" section.

Then, when you need a service, you can call the get static method, which will call your lazily loaded service.
 
The "application services" is reserved for static services, which never need preparation and don't need to be saved to memory,

add your static services in this section.
 

In kif, **modules** often expose their services using a bridge.
 
In terms of design, this means that the services in kif are organized by context (modules, core, other classes...)
rather than all put together in the same place.


I did so because I preferred to work with multiple small files rather than one single huge file.

But now I also have the feeling that this is the right places for services to be (i.e. a service belongs to a module
and therefore shall be found in that module's directory).


   
   




Module
-----------
2016-12-14


A module class is the ambassador of a module (or kif module to avoid disambiguation).

This means the module class is responsible for hooking with other module's services (via the bridges provided by the other modules,
see the "Bridge" definition in the nomenclature section).

In other words, there should only hooking code in a module.

The code specific to a module (private code or module internal code) should be organized under the **Util** directory of the module.

Modules are by default located in the **class-modules/** directory.

Note: this is only true if you use the kif definition of a module. If you have your own definition of a module, then you can just
ignore these conventions and implement your own system.





Class naming conventions
-------------------------
2016-12-14


Without conventions, things could become messy very quickly.

Kif uses a simple system based on class suffix for class naming.

For a given MyClass object, kif uses the following conventions:

- MyClassConfig: the configuration of the class
    - it exposes only what is configurable in your class
    - all methods should be "public static" and return configuration data
    - note that the configuration is done directly inside the class, which is part of what makes kif a simple and intuitive framework
- MyClassBridge: the bridge for the class
    - it exposes the services of the class to the outer world (see "Bridge" term in the nomenclature section for more details)
- MyClassUtil: the main utility class 
    - use this only if your class is small enough that it doesn't require multiple utilities
    - if your class use multiple utilities, use the dedicated Util directory instead
- Util/: a directory that contains utilities for the MyClass class
- MyClassModule: if your class is a module, it should be named MyClassModule, and not just MyClass.
    - this class is the ambassador of the module (see "Module" term in the nomenclature section for more details) 







Private TODO:
================
- Http Response helpers (and the file from symfony)
- normalized way to inject into the html title





Version history
==============


- 1.0.0 - 2016-12-14

    Initial commit   


