<?php

// Include the loadEnv function
require_once 'loadEnv.php';

// Load the .env file
$filePath = __DIR__ . '/.env';
loadEnv($filePath);

// Access environment variables
$dbHost = getenv('DB_HOST');
$dbPort = getenv('DB_PORT');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$HUYE_DB_NAME = getenv('HUYE_DB_NAME');

$connection=mysqli_connect($dbHost,$dbUser, $dbPassword,$HUYE_DB_NAME,$dbPort);
if($connection){
// echo'connected';
}

?>