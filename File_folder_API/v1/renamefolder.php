<?php

ini_set("display_errors", 1);

 require '../vendor/autoload.php';
 use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

// including files
include_once("../config/database.php");
include_once("../classes/Users.php");

//objects
$db = new Database();

$connection = $db->connect();

$user_obj = new Users($connection);


if($_SERVER['REQUEST_METHOD'] === "POST"){

            // body
        $data              = json_decode(file_get_contents("php://input"));

        $headers           = getallheaders();

        $cwd               = getcwd();     //path variable

        $id                =  $_POST['id'];         //database table id
        
        //updates folder records on server

        $old_folder_name   = "../" . $_POST['old_name'];        //current name on server that needs to change

        $new_folder_name   = "../" .rand(1000, 100000)."_". $_POST['path']; //insert new name

        if(is_dir($old_folder_name) && !is_dir($new_folder_name))
        {
            rename($old_folder_name, $new_folder_name);
            http_response_code(200); // ok
            echo json_encode(array(
            "status" => 1,
            "message" => "folder renamed successfully"
            ));
        }
        else
        {
            http_response_code(200); // ok
            echo json_encode(array(
            "status" => 0,
            "message" => "failed to rename"
            ));
        }

        //update folder records on database tables

    if(!empty($new_folder_name))
    {
        try{

            //genetate token to authenticate user

            $jwt                 = $headers["Authorization"];

            $secret_key          = "owt125";
     
            $decoded_data        = JWT::decode($jwt, $secret_key, array('HS512'));
    
            $user_obj->user_id   = $decoded_data->data->id;   //user login id
    
            $user_obj->path      = $cwd.$new_folder_name;       //rename folder path on db
    
            $user_obj->id        = $id;             //delete table id
    
            if($user_obj->rename_folder())
            {
                http_response_code(200); //success
                echo json_encode(array(
                  "status" => 1,
                  "message" => "successfully updated"
                ));  
            }
            else{
                http_response_code(500); //server error
                echo json_encode(array(
                  "status" => 0,
                  "message" => "failed to update"
                ));
            }
        }
        catch(Exception $ex)
        {
            http_response_code(500); //server error
            echo json_encode(array(
            "status" => 0,
            "message" => $ex->getMessage()
            ));
        }
       
    }
    else
    {
        http_response_code(404); //not found
        echo json_encode(array(
        "status" => 0,
        "message" => "all data needed"
        ));
    }
    

}



?>