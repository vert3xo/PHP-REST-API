<?php
header('Content-Type: application/json');

$message = array(
    "message" => "Welcome to simple REST API made in PHP"
);
echo json_encode($message);
die();