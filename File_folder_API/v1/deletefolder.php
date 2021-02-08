<?php
ini_set("display_errors", 1);

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

//including headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// including files
include_once("../config/database.php");
include_once("../classes/Users.php");

//objects
$db           = new Database();

$connection   = $db->connect();

$user_obj     = new Users($connection);


if($_SERVER['REQUEST_METHOD'] === "GET"){

        $headers  = getallheaders();

        $jwt      = $headers["Authorization"];

          //delete folder from server

          function remove_directory($directory)
         {
            if (!is_dir($directory)) return;

            $contents = scandir($directory);  //scan folder for any files within

            unset($contents[0], $contents[1]);

            //loop folder and delete available files

            foreach($contents as $object) 
            {
                $current_object = $directory.'/'.$object;

                if (filetype($current_object) === 'path') 
                {
                    remove_directory($current_object);
                } 
                else 
                {
                    unlink($current_object);    
                }
            }

            rmdir($directory);
          }

        $dir = basename($_GET['path']);
        if ($dir[0] != '.') remove_directory('../'.$dir);
    
        //delete folder path from database table
    try
    {
        $folder_id             = isset($_GET['path']) ? $_GET['path'] : "";

        $cwd                   = getcwd();

        //generate token

        $secret_key            = "owt125";

        $decoded_data          = JWT::decode($jwt, $secret_key, array('HS512'));

        $user_obj->user_id     = $decoded_data->data->id;   // login user id

        //execute the request

        if(!empty($folder_id))
         {
          $user_obj->path = $cwd . "../" .$folder_id;

          if($user_obj->delete_folder())
            {
              http_response_code(200);
              echo json_encode(array(
              "status" => 1,
              "message" => "success"
              ));
            }
            else
            {
              http_response_code(500);
              echo json_encode(array(
              "status" => 0,
              "message" => "failed to delete"
              ));
            }
        }
        else
         {
          http_response_code(404);
          echo json_encode(array(
          "status" => 0,
          "message" => "all folder needed"
          ));
        }

    }catch(Exception $ex){
      http_response_code(500); // no files found
      echo json_encode(array(
      "status" => 0,
      "message" => $ex->getMessage()
      ));
    }
}

 ?>
