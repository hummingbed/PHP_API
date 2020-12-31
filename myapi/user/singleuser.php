<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../config/CreateDb.php';
include_once '../models/User.php';


$database   = new CreateDb();
$db         = $database->connect();

$user       = new User($db);

$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$user->single_user();

$user_arr = array(
    'id'            => $user->id,
    'user_name'     => $user->user_name,
    'user_email'    => $user->user_email,
    'user_password' => $user->user_password
);

print_r(json_encode($user_arr));

?>