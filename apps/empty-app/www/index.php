<?php


use Router\RouterBridge;

require_once __DIR__ . "/../init.php";


ob_start(); // ob start ensures that we have the ability to do http responses (301, 404) from the pages
try {



    //--------------------------------------------
    // ROUTER
    //--------------------------------------------
    /**
     * THIS IS THE ROUTER MAP.
     * Add your page routes here...
     *
     */
    $uri2pagesMap = [
        '/' => 'home.php',
        '/test' => 'test.php',
    ];


//    RouterBridge::decorateUri2PagesMap($uri2pagesMap); // have modules?

    /**
     * This is the router code, you shouldn't edit below this line
     */
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    if ('' !== URL_PREFIX && URL_PREFIX === substr($uri, 0, strlen(URL_PREFIX))) {
        $uri = substr($uri, strlen(URL_PREFIX));
    }
    Spirit::set('uri', $uri);


    $page = "404.php";
    if (array_key_exists($uri, $uri2pagesMap)) {
        $page = $uri2pagesMap[$uri];
    }


} catch (\Exception $e) {
    // exception handling: fallback strategy
    $page = 'exception.php';
}

require_once APP_ROOT_DIR . "/pages/" . $page;
echo ob_get_clean();