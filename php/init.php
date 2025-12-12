<?php
// Connection for all the pages

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//will be taken from the data/users.txt
$currentUser = [
    'login'        => 'demo_user',
    'yellow_stars' => 120,
    'blue_stars'   => 35,
];
