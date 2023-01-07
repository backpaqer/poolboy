<?php
/**
 * Created by PhpStorm.
 * User: ZEPHYR
 * Date: 31-Mar-19
 * Time: 11:29 AM
 */
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'poolboy');
    define('DB_PASSWORD', 'poolpass');
    define('DB_DATABASE', 'poolboy');
    $connect = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
