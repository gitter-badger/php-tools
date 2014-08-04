<?php

require "Router.php";
/* defined a "404 - not found" function */

function err_not_found_404() {
    echo "404 - page not found...";
}

/* new route. */
$route = new Router();
/* Assign static routes. */
$route->assign("/", function() {
    echo "this is home page.";
});
$route->assign("/about", function() {
    echo "this is the about page.";
});
/* Assign variables routes. */
$route->assign("/user/{user_id}", function($uid) {
    echo "user $uid profile";
});

$route->assign("/give/{user_id}/{object}", function($uid, $object) {
    echo "give $object to user $uid";
});

try {
    $route->proceed();
} catch (Err_not_found_404 $e) {
    err_not_found_404();
}