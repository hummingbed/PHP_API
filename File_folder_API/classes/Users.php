
<?php

class Users{

    // define properties
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $files;
    public $id;
    
    private $conn;
    private $users_tbl;
    private $files_table;
    private $folder_table;


    public function __construct($db){
        $this->conn = $db;
        $this->users_tbl = "users";
        $this->files_table = "file_entries";
        $this->folder_table = "folders";
     }

    //   register user

     public function create_user(){

        $user_query = "INSERT INTO ".$this->users_tbl." SET username = ?, email = ?, password = ?";
    
        $user_obj = $this->conn->prepare($user_query);
    
        $user_obj->bind_param("sss", $this->username, $this->email, $this->password);
    
        if($user_obj->execute()){
          return true;
        }
    
        return false;
      }

    //   prevent multiple registration by checking user exist

      public function check_email(){

        $email_query = "SELECT * from ".$this->users_tbl." WHERE email = ?";
    
        $usr_obj = $this->conn->prepare($email_query);
    
        $usr_obj->bind_param("s", $this->email);
    
        if($usr_obj->execute()){
    
           $data = $usr_obj->get_result();
    
           return $data->fetch_assoc();
        }
    
        return array();
      }

      public function check_login(){

        $email_query = "SELECT * from ".$this->users_tbl." WHERE email = ?";
    
        $usr_obj = $this->conn->prepare($email_query);
    
        $usr_obj->bind_param("s", $this->email);
    
        if($usr_obj->execute()){
    
           $data = $usr_obj->get_result();
    
           return $data->fetch_assoc();
        }
    
        return array();
      }

        //upload files in database query

     public function files_upload(){

      $file_query = "INSERT INTO ".$this->files_table." SET user_id = ?, file_name = ?, mime = ?, file_size = ?";
    
      $upload_obj = $this->conn->prepare($file_query);
  
      $upload_obj->bind_param("isss", $this->user_id, $this->file_name, $this->mime, $this->file_size);
  
      if($upload_obj->execute()){
        return true;
      }
  
      printf("Error: %s.\n", $upload_obj->error);

        return false;
    
     }


    public function delete_folder(){

      $delete_folder_query = "DELETE from ".$this->folder_table." WHERE path = ?";
  
      $user_delete_obj = $this->conn->prepare($delete_folder_query);
  
      $user_delete_obj->bind_param("s", $this->path);
  
      if($user_delete_obj->execute()){
        
        return true;
      }
  
      return false;

    }

    //create folder in database query

     public function folder_create(){
       
      $folder_query = "INSERT INTO ".$this->folder_table." SET user_id = ?, path = ?";
    
      $upload_obj = $this->conn->prepare($folder_query);
  
      $upload_obj->bind_param("is", $this->user_id, $this->path);
  
      if($upload_obj->execute()){

        return true;
      }
  
      printf("Error: %s.\n", $upload_obj->error);

        return false;
    
     }


     public function get_all_files(){

      $get_files_query = "SELECT * from ".$this->files_table." WHERE user_id = ? ORDER BY id DESC";
  
      $user_files_obj = $this->conn->prepare($get_files_query);
  
      $user_files_obj->bind_param("i", $this->user_id);
  
      $user_files_obj->execute();
  
      return $user_files_obj->get_result();
  
    }

    public function rename_folder(){

      $rename_folder_query = "UPDATE folders SET path = ? WHERE id = ?";

      $rename_folder = $this->conn->prepare($rename_folder_query);

      $this->path = htmlspecialchars(strip_tags($this->path));

      $this->id = htmlspecialchars(strip_tags($this->id));

      $rename_folder->bind_param("si", $this->path, $this->id);

      if($rename_folder->execute()){

        return true;
      }

        return false;

     }   

     
    // public function move_files(){

    //   $move_file_query = "UPDATE file_entries SET file_name = ? WHERE id = ?";

    //   $move_file = $this->conn->prepare($move_file_query);

    //   $this->file_name = htmlspecialchars(strip_tags($this->file_name));
    //   $this->id = htmlspecialchars(strip_tags($this->id));

    //   $move_file->bind_param("si", $this->file_name, $this->id);

    //   if($move_file->execute()){

    //     return true;
    //   }

    //     return false;

    //  }    

   

    // public function rename_files(){

    //   $rename_query = "UPDATE " .$this->files_table. "SET files = ? WHERE id = ?";

          
    //   $rename_obj = $this->conn->prepare($rename_query);

    //   $this->files =htmlspecialchars(strip_tags($this->files));
    //   $this->id =htmlspecialchars(strip_tags($this->id));
  
    //   $rename_obj->bind_param("si", $this->files, $this->id);
  
    //   if($rename_obj->execute()){
    //     return true;
    //   }
  
    //   printf("Error: %s.\n", $rename_obj->error);

    //     return false;
  
    // }





}


?>