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
$db           = new Database();

$connection   = $db->connect();

$user_obj     = new Users($connection);

if($_SERVER['REQUEST_METHOD'] === "POST"){
  
  $data     = json_decode(file_get_contents("php://input"));

  $headers  = getallheaders(); 
       
       $cwd = getcwd();     // gets folder path

       //create folder in server

       if(isset($_POST['path']))
       {
          $folder_name =  rand(1000, 100000)."_".$_POST['path'];

          if(!file_exists($folder_name))      /* Check folder exists or not */
          {
            @mkdir('../'.$folder_name);     /* Create folder by using mkdir function */

            http_response_code(200); // ok
            echo json_encode(array(
            "status" => 1,
            "message" => "folder has been created in server"
            ));
          }
        }
     
        //create foldername and path in database table

  if(!empty($folder_name))
 {
    try{
            //generate token for logged in users

          $jwt          = $headers["Authorization"];

          $secret_key   = "owt125";
  
          $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));

          //inserts paths and specific user_id into database table

          $user_obj->user_id    = $decoded_data->data->id;      //Authenticats user to log in

          $user_obj->path       = $cwd . "../" .$folder_name;   //inserts path in database table

          if($user_obj->folder_create())
         {
            http_response_code(200); // ok
            echo json_encode(array(
            "status" => 1,
            "message" => "folder name has been created in database"
            ));
          }
          else
          {
            http_response_code(500); //server error
            echo json_encode(array(
            "status" => 0,
            "message" => "Failed to create"
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
    http_response_code(404); // not found
    echo json_encode(array(
    "status" => 0,
    "message" => "Folder name needed"
    ));
  }  
}