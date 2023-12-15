<?php 

class User{

    private $db;

    public function __construct(){
        $this->db=Database::getInstance()->getConnection();
        try{
        $result=$this->db->query("SELECT 1 FROM `users` LIMIT 1");    
        }catch(PDOException $e){
        $this->createTable();
        }
    }

public function createTable(){
$roleTableQuery="CREATE TABLE IF NOT EXISTS `roles` (
        `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
        `role_name` VARCHAR(255) NOT NULL,
        `role_description` TEXT
)";


    $userTableQuery="CREATE TABLE IF NOT EXISTS `users` (
        `id` INT(11) NOT NULL  AUTO_INCREMENT ,
        `username` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `email_verification` TINYINT(1)  NOT NULL DEFAULT 0,
        `password` VARCHAR(255) NOT NULL,
        `is_admin` TINYINT(1)  NOT NULL DEFAULT 0,
        `role` INT(11)  NOT NULL DEFAULT 0,
        `is_active` TINYINT(1)  NOT NULL DEFAULT 1,
        `last_login` TIMESTAMP NULL, 
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
        PRIMARY KEY(`id`),
         FOREIGN KEY (`role`) REFERENCES `roles` (`id`)
        )";

try{
   $this->db->exec($roleTableQuery);
   $this->db->exec($userTableQuery);
    return true;
   }catch(PDOException $e){
    return false;
   }

}

    public function readAll(){
        try{
$stmt=$this->db-> query("SELECT * FROM  `users`");
        

        $users=[];

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            $users[]=$row;
        }
        return $users;
    }catch(PDOException $e){
        return false;
       };
    //    Database::tte( $stmt);
    }

    public function create($data){
        //  Database::tte( $data);
        $username=$data['username'];
        $email=$data['email'];
        $password=password_hash($data['password'], PASSWORD_DEFAULT);
        $role=$data['role'];
       $created_at=date('Y-m-d H:i:s');
    //    Database::tte($_POST['password'].' :'.$password);
       $query=" INSERT INTO users (username,email,password,role,created_at) VALUES (?,?,?,?,?)";
       
      try{
       
        $stmt=$this->db->prepare($query);
        
         $stmt->execute([$username ,$email, $password,$role,$created_at]);
        //  Database::tte( $stmt);
        echo "Query executed successfully!\n";
        return true;
      }catch(PDOException $e){
        return false;
       } 
    }


    public function update($id, $data){

      
  $username=$data['username'];
  $admin=!empty($data['is_admin'])&&$data['is_admin']!==0 ? 1 : 0;
  $role=$data['role'];
  $email=$data['email'];
  $is_active=isset($data['is_active']) ? 1:0;
//   Database::tte( $role);

  $query="UPDATE `users` SET `username`= ?,`email` = ?,`is_admin`=?,`role` = ?,`is_active` = ? WHERE `id`=?";
  try{
    $stmt=$this->db->prepare($query);
    $stmt->execute([$username, $email, $admin,$role,$is_active,$id]);
    return true;
   }catch(PDOException $e){
    return false;
   }
        }

//   Database::tte("username: ". $username."  admin: ".$admin."  role: ".$role.
//   "  email: ".$email."  is_active: ".$is_active);

    public function read($id){
        $query="SELECT * FROM users WHERE id = ?";

        try{
            $stmt=$this->db->prepare($query);
            $stmt->execute([$id]);
            $res= $stmt->fetch();
            return $res;
           }catch(PDOException $e){
            return false;
           }
    }

    
    public function delete($id){

        $query="DELETE FROM users WHERE id = ?";

            try{
                $stmt=$this->db->prepare($query);
                $stmt->execute([$id]);
                return true;
               }catch(PDOException $e){
                return false;
               }        
    }
    
}