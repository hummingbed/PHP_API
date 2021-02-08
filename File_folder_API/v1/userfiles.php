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
$db = new Database();

$connection = $db->connect();

$user_obj = new Users($connection);

if($_SERVER['REQUEST_METHOD'] === "GET"){

    $headers = getallheaders();

    $jwt = $headers["Authorization"];

    try{

      $secret_key = "owt125";

      $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));

      $user_obj->user_id = $decoded_data->data->id;

      $user_files = $user_obj->get_all_files();

      if($user_files->num_rows > 0){

        $user_files_arr = array();

        while($row = $user_files->fetch_assoc()){

           $user_files_arr[] = array(
             "id" => $row['id'],
             "user_id" => $row["user_id"],
             "file_name" => $row["file_name"],
             "created_at" => $row["created_at"]           

           );
        }

         http_response_code(200); // Ok
         echo json_encode(array(
           "status" => 1,
           "user_files" => $user_files_arr
         ));

      }else{
         http_response_code(404); // no files found
         echo json_encode(array(
           "status" => 0,
           "message" => "No user files found"
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
