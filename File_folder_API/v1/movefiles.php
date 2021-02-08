<?php

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");



if($_SERVER['REQUEST_METHOD'] === "POST"){

    $data = json_decode(file_get_contents("php://input"));

    $file_name = $_FILES['file_name']['name'];
    $tempName = $_FILES['file_name']['tmp_name'];
    
    if(isset($file_name))
    {
        if(!empty($file_name))
        {
            $path = "../newfileDir/";
            if(move_uploaded_file($tempName, $path.$file_name))
            {
                http_response_code(200); // ok
                echo json_encode(array(
                  "status" => 1,
                  "message" => "file has been moved successfully"
                ));
            }
        }
    }
  
}