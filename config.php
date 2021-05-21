<?php

//Database connection information

$db['host'] = 'localhost';
$db['username'] = 'root';
$db['password'] = '';
$db['database'] = 'federicotensi';


$mysqli = new mysqli($db['host'], $db['username'], $db['password'], $db['database']);


//Check connection status
if ($mysqli->connect_error) {
    echo 'Connection failed: ' . $mysqli->connect_error;
    exit();
}
