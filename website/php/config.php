<?php
/**
 * Created by PhpStorm.
 */
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', '{username}');
    define('DB_PASSWORD', '{userpass}');
    define('DB_DATABASE', 'poolboy');
    $connect = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
