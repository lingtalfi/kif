How to create a kif application
====================================
2016-12-14



In this document, I (or you) create a kif application from scratch.


The goal of this document is to teach you the kif workflow.



Download the app
===================
2016-12-14


First step is to download your **app/** directory.


In this repository, you will find the following **app/** directories:

- **empty-app/**, the ugliest app ever, no kidding..., but that's what pure developers like (or am I just too lazy?)


Select the directory that corresponds best to your needs, and rename it **app/** (it's just a convention,
if you don't want to rename it, you can leave it as is).



 

Configure your app
=====================
2016-12-14


The first thing to do then is to review your application configuration, section by section, removing section you don't need. 

The configuration process is generally done once per application.


Open the **init.php** file and configure/remove the sections one by one.


If you are not sure of how to configure a section, you can refer to the documentation for this section.


Once this is done, you should open the **www/index.php** file and customize it according to your application needs.

If you are not sure of how to customize your **www/index.php**, please refer to the documentation (the "index.php" section).




Make it alive
=================
2016-12-14


I hate this part because it depends on your machine and the technologies you are using.

I personally use nginx, php and mysql, all installed with brew (on mac),
but you could also use mamp, or wamp?, or any other system.

So I will not be very helpful in this part.
 

Just do the following:
  
- make your web server point to the **www/** directory   
- make your web server serve the **www/index.php** for any "virtual request" (a request that does not map to an actual file)
   - note that if you use apache, there is already a .htaccess file that might do the job (**www/.htaccess**)
   - if you use nginx, see my sample below
- configure your web server's "virtual hosts" if you want
- open your browser with the url of your web application
    - you should see your application's home page



Nginx sample
---------------
2016-12-14


```nginx
 server {
    listen 80; 
    server_name kif;
	index index.php;

    root "/pathto/my/project/app/www";

    try_files $uri $uri/ /index.php?$query_string;
    
	location ~ \.php {
	    include fastcgi_params;
	    include fastcgi.conf;
	    fastcgi_pass 127.0.0.1:9000;
	}
 }
```



Permissions
===============
2016-12-14


We also need to configure the permissions properly. 

The **log/** directory needs to be writable.

I'm not going to tell you how to make a directory writable, I assume this is common knowledge.
 
If you don't know that, then you will find plenty of help on the internet.





Create pages
===============
2016-12-14


Now that this installation steps are done, you can start creating pages.

Here is my main workflow, you can use it if you want.


- create the prototype
- create the layout
- do an iteration, and again, and again...
    - make a page
    - factorize to a module if possible



If you use modules, be aware that by convention, for a module named MyModule, I like to create a **pages/modules/myModule** subdirectory 
which contains all the pages created by this module (notice the camel case for the myModule subdirectory). 

This organization helps with the process of installing/uninstalling modules. 







The prototype
---------------

What I call prototype is a fully integrated html page with fake data (the real data will be injected later with php).


- I like to start with the design and the html prototype 
    - I use the php extension to benefit the scripting power of php (loops basically)
    - I will create the html page I like, including all assets
    - I would use fake loops for repeating elements like lists,
     I'm only concerned with the end visual result at that stage
    - lorem ipsum is your friend
     

Creating the prototype is to me the most important job, since the front user
will have her first reaction based on the html page she sees.
 
That's where you work on ux problems, design problems, etc...

Doing the php part is just piping/branching things together, but the real
hard work is creating the prototype that really represents your brand.

So, it should take some time...

When I finish a prototype, I like to make a copy of my work in a **app/private** directory, just in case 
I need it later.




Create the layout
----------------------

When the prototype is done, time to make the Layout.

The Layout is basically an object that we will call to render the html page.
 
The benefit is obvious: rather than writing all the html, we just call the layout with a few parameters.


Here is an example final code using the Layout object. 

```php
<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => "home.php",
])->display();



```

So it's about three lines of code, vs 500 more lines of html, depending on your layout.

If you don't know how to create a Layout object, you can use the default Layout of any kif application as a model (in the **class/Layout** directory).

Basically, the idea is this:

- identify the parts common and variable parts
    - in your layout: what elements are common to all pages, and what elements need to change from page to page 
- extract the variable elements and name them as you wish (body, rightColumn, ...)
    - the variable elements are called **layout elements**
- for each page, create the necessary **layout elements** in the dedicated **layout-elements/** directory
    - if you have multiple layouts, it might be a good idea to create subdirectories (for instance **layout-elements/layout1**),
    and even more subdirectories if your layout contain multiple layout elements (for instance **layout-elements/layout1/rightColumn**)
- after that, you just need to do the branching between the layout and its elements, as shown in the example code above    


Repeat the process for all the prototypes you have (i.e. you should have one Layout object per prototype).


If you use modules, and if those modules are aware of your application layouts, then be aware that by convention, for a module named MyModule, 
I like to create a **layout-elements/(layout1/rightColumn/)?modules/myModule** subdirectory 
which contains all the layout elements created by this module for the given page and layout (notice the camel case for the myModule subdirectory). 

This organization helps with the process of installing/uninstalling modules.
  
 
 
 

Make a page
----------------


### the page file

This is an important step. If you understand how to make a page, then you will fully understand how 
any kif application works.
 
The best part is that it's an intuitive process that you will only do once and never forget. 


To create a page, I like to copy an existing page.

All pages are in the **pages/** directory.

If there is no page, just create one, here is an example code again:



```php
<?php


use Layout\Layout;

Layout::create()->setElementFiles([
    'body' => "home.php",
])->display();



```


Simple isn't it?

The only thing to be careful about is the general organization of your pages.

For instance, if you use modules, it's a good ideas to create a **pages/modules** subdirectory.

Keeping things organized makes it even more intuitive when you need to copy/find a page.



### the layout element file

Then, create the layout elements.

Again, I like to copy an existing layout element if possible, and keeping things organized is important as well.


A typical layout-element would contain some html code, like this for 
instance (404 example from the [nullos admin project](https://github.com/lingtalfi/nullos-admin)).


```php
<div class="freepage">
    <p>
        <?php echo __("This is the 404 page.", "page-404"); ?>
        <br>
        <?php echo __("You are lost.", "page-404"); ?>

    </p>
</div>
```


Of course this is a simple example, and it can get as complex as you wish.

Notice that in the above example, I used the **__** (double underscore) function, which translates messages (we will see that later).


### hook your page to the router

Now your page is ready to go live.

The last step is to choose the url to access it.

Choose the url you want, and hook it into the router, which resides in the **www/index.php** file.

Depending on your router complexity, hooking a page might be as simple as adding an entry to a php array,
or using the RouterBridge object (if you use a simple router with modules), or use any other object that you want.

In all cases, if you are lost, you start by reading the **www/index.php** file and in no time you are back on the rails.

Do you see how simple and intuitive it is to work with kif yet?



Creating modules
====================
2016-12-14

Why do you need to create module?

Well, as always in kif, don't create a module if you don't need it.

A module is basically a piece of code which hooks itself to the bridges provided by the application.

For instance, the default router provides a service to decorate the simple array that maps uri to pages.

Imagine you want to create a blog application, and you want to be able to re-use the same blog application
in your next kif application.

Hmmm, the word re-use is a often an indicator that a module might be a solution.

Indeed in this blog example, you could create a module which would automatically add some mappings
to the router (for instance the edit-post page, the list-posts page, and so on...).

In that case, a module is a good candidate for your implementation.

Of course, creating a blog module is actually a complex task, which requires a lot more than
just hooking to the router, but starting little and growing only if necessary, you will see that the road to making
a new blog module is actually straightforward and logical.












