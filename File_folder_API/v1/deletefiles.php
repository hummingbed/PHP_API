<?php
// ini_set("display_errors", 1);

error_reporting(0);

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

//including headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// including files
include_once("../config/database.php");
include_once("../classes/Users.php");

//objects
$db = new Database();

$connection = $db->connect();

$user_obj = new Users($connection);


if($_SERVER['REQUEST_METHOD'] === "GET"){

        $data = json_decode(file_get_contents("php://input"));

        $headers = getallheaders();

        $files_id = isset($_GET['id']) ? $_GET['id'] : "";

        if(isset($_GET['id']))
        {
          //query database table that deletes files from server
          $delete_files_query = "SELECT * from file_entries WHERE id = '$files_id'";    
    
          $delete_id          = mysqli_query($connection, $delete_files_query);

          $path               = '../fileDir/';
          
          //find match from databse table while looping and delete from derver
          while($row  = mysqli_fetch_array($delete_id))
          {
            $delete_files = $row['file_name'];
          }

          unlink($path.$delete_files);
        }

        if(!empty($files_id))
        {
            try{
                $jwt                  = $headers["Authorization"];

                $secret_key           = "owt125";
        
                $decoded_data         = JWT::decode($jwt, $secret_key, array('HS512'));
      
                $user_obj->user_id    = $decoded_data->data->id;   //authenticate user

                //query database table that deletes files from database
      
                $delete_files_query   = "DELETE from file_entries WHERE id = '$files_id'";
        
                $delete_id            =   mysqli_query($connection, $delete_files_query);
    
                if($delete_id)
                {           
                  http_response_code(200);    //success
                  echo json_encode(array(
                    "status" => 1,
                    "message" => "files deleted successfully"
                  ));
                }
                else
                {
                  http_response_code(500);    //server error
                  echo json_encode(array(
                    "status" => 0,
                    "message" => "failed to delete files"
                  ));
                }

              }catch(Exception $ex){
                http_response_code(500); //server error
              echo json_encode(array(
                "status" => 0,
                "message" => $ex->getMessage()
              ));
            }
        }
        else{
            http_response_code(404);    //not found error
            echo json_encode(array(
              "status" => 0,
              "message" => "files not found"
            ));
        }

  }
  else{
    http_response_code(503);    //service unavailable
    echo json_encode(array(
      "status" => 0,
      "message" => "Access denied"
    ));
  }

 ?>
