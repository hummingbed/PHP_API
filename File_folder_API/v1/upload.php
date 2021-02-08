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
   $data = json_decode(file_get_contents("php://input"));

   $headers = getallheaders();

          //upload file to server
    is_uploaded_file($_FILES["file_name"]["tmp_name"]);   
    
        $tmp_file         = $_FILES['file_name']['tmp_name'];       //tempoary file name

        $file_name        = rand(1000, 100000)."_".$_FILES['file_name']['name'];    //original file name with unique generated code

        $file_size        = $_FILES['file_name']['size'];       //files size

        $mime             = $_FILES['file_name']['type'];     // get file type e.g png pdf

        $upload_dir       = "../fileDir/".$file_name;         // file location path

        if(move_uploaded_file($tmp_file, $upload_dir))        // upload function
        {
            http_response_code(200); // file upload successful
            echo json_encode(array(
            "status" => 1,
            "message" => "File uploaded"
            ));
        }else{
            http_response_code(404); // not found
            echo json_encode(array(
            "status" => 0,
            "message" => "upload failed"
            ));
        }

    //insert file info in database
   if(!empty($file_name))
   {
        try{

            $jwt          = $headers["Authorization"];

            $secret_key   = "owt125";
    
            $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));   

            $user_obj->user_id       = $decoded_data->data->id;   //user_id to log user in

            $user_obj->file_name     = $file_name;          //bind sql query with server

            $user_obj->mime          = $mime;               //bind sql query with server

            $user_obj->file_size     = $file_size;          //bind sql query with server

            

            if($user_obj->files_upload())       //calling the public function containig the query
            {
              http_response_code(200); // ok
              echo json_encode(array(
                "status" => 1,
                "message" => "files has been created"
              ));
            }
              else{
                http_response_code(500); //server error
                echo json_encode(array(
                  "status" => 0,
                  "message" => "Failed to upload"
                ));
              }

        }
          catch(Exception $ex){
          http_response_code(500); //server error
          echo json_encode(array(
          "status" => 0,
          "message" => $ex->getMessage()
          ));
        }
    }
    else{
      http_response_code(404); // not found
      echo json_encode(array(
        "status" => 0,
        "message" => "Files needed"
      ));
    }
    
}