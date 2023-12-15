<?php  

class AuthUser{

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
        `create_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
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

    public function register($username,$email,$password){
        // Database::tte($username.$email.$password); (?,?,?,?),$role
        $created_at=date('Y-m-d H:i:s');

        // $query="INSERT INTO users (username, email, password, role, created_at) 
        // VALUES ('vasia' ,'vasia@rambler.ru', 'password', 1, '2021-09-12');";

        $query = "INSERT INTO users (username, email, password, created_at) 
        VALUES (?,?,?,?)";
    // $query="INSERT INTO users (username,email,password,created_at) VALUES ($username,$email,$password,$created_at)";    
        try{
            // $stmt = $this->db->query($query);
            // $stmt=$this->db->prepare($query);
            // $stmt->execute();
     $stmt=$this->db->prepare($query);
     $stmt->execute([$username ,$email,$password, $created_at]);
        return true;
    }catch(PDOException $e){
        return false;
       }
    //    Database::tte( $stmt);
    }

    public function login($email,$password){
      try{
        $query=" SELECT * FROM users WHERE email=? LIMIT 1";
        $stmt=$this->db->prepare($query);
         $stmt->execute([$email]);
        // Database::tte( $result);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);
if($user&&password_verify($password,$user['password'])){
    return $user;
}
    return false;
      }catch(PDOException $e){
        return false;
       } 
    }


    public function findByEmail($email){

  $query="SELECT * FROM `users` WHERE `email` = ? LIMIT 1";
  try{
    $stmt=$this->db->prepare($query);
    $stmt->execute([$email]);

    $user=$stmt->fetch(PDO::FETCH_ASSOC);
    // Database::tte( $user);
    return $user ? $user : false;
   }catch(PDOException $e){
    return false;
   }
        }

//   Database::tte("username: ". $username."  admin: ".$admin."  role: ".$role.
//   "  email: ".$email."  is_active: ".$is_active);

   
    
}