<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');



include_once '../config/CreateDb.php';
include_once '../models/User.php';



$database   = new CreateDb();
$db         = $database->connect();

$user       = new User($db);

if($_SERVER['REQUEST_METHOD'] === "POST"){

    $data = json_decode(file_get_contents("php://input"));

    $user->user_name     = $data->user_name;

    $user->user_email    = $data->user_email;

    $user->user_password = $data->user_password;


    if($user->create()){
        echo json_encode(
            array('message' => 'User Created')
        );
    }
}else{
    echo json_encode(
        array('message' => 'No User Created')
    );
}


?>