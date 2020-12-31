

<?php

class User{
    private $conn;
    private $table = 'user';

    public $id;
    public $user_name;
    public $user_email;
    public $user_password;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT u.id,
                         u.user_name,
                         u.user_email,
                         u.user_password
                         FROM
                         '. $this->table .' u';

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
    }

    public function single_user(){
        $query = 'SELECT u.id,
                         u.user_name,
                         u.user_email,
                         u.user_password
                         FROM
                         '. $this->table .' u
                         WHERE
                         u.id = ?
                         LIMIT 0,1
                         ';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->user_name = $row['user_name'];
        $this->user_email = $row['user_email'];
        $this->user_password = $row['user_password'];
    }
    public function create(){
        $query = 'INSERT INTO ' . $this->table . '
                    SET 
                    user_name       = :user_name,
                    user_email      = :user_email,
                    user_password   = :user_password
                     ';

                     $stmt = $this->conn->prepare($query);

                     $this->user_name       = htmlspecialchars(strip_tags($this->user_name));
                     $this->user_email      = htmlspecialchars(strip_tags($this->user_email));
                     $this->user_password   = htmlspecialchars(strip_tags($this->user_password));

                    $stmt->bindParam(':user_name', $this->user_name); 
                    $stmt->bindParam(':user_email', $this->user_email);
                    $stmt->bindParam(':user_password', $this->user_password);

                    if($stmt->execute()){
                        return true;
                    }
                    printf("Error: %s.\n", $stmt->error);

                        return false;
                    
    }

    public function modify(){
        $query = 'UPDATE ' . $this->table . '
                    SET 
                    user_name       = :user_name,
                    user_email      = :user_email,
                    user_password   = :user_password
                   WHERE 
                    id = :id
                     ';

                     $stmt = $this->conn->prepare($query);

                     $this->id              = htmlspecialchars(strip_tags($this->id));
                     $this->user_name       = htmlspecialchars(strip_tags($this->user_name));
                     $this->user_email      = htmlspecialchars(strip_tags($this->user_email));
                     $this->user_password   = htmlspecialchars(strip_tags($this->user_password));

                    $stmt->bindParam(':id', $this->id); 
                    $stmt->bindParam(':user_name', $this->user_name);
                    $stmt->bindParam(':user_email', $this->user_email);
                    $stmt->bindParam(':user_password', $this->user_password);

                    if($stmt->execute()){
                        return true;
                    }
                    printf("Error: %s.\n", $stmt->error);

                        return false;
                    
    }
    

    public function delete(){
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id ';

        $stmt  = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }
        printf("Error: %s.\n", $stmt->error);

            return false;

    }
                    
                     
}



?>
