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
    return "this is home page.";
});
/* Assign to file */
$route->assign("/about", "about.html");
/* Assign to text */
$route->assign("/about/goods", "About goods");



/*
 *  Assign variables routes. 
 */
$route->assign("/user/{user_id}", "profile.html");

$route->assign("/give/{user_id}/{object}", function($uid, $object) {
    //Remove object from current user to next user.
    return "give $object to user $uid";
});


try {
    echo $route->proceed();
} catch (Err_not_found_404 $e) {
    err_not_found_404();
}