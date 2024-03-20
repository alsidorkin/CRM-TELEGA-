<?php 
namespace models\users;

use models\Database;
class User{

    private $db; 

    public function __construct(){
        $this->db=Database::getInstance()->getConnection();
        try{
        $result=$this->db->query("SELECT 1 FROM `users` LIMIT 1");    
        }catch(\PDOException $e){
        $this->createTable();
        }
    }

// проверка на наличие таблиц и записей в базе

private function rolesExist(){
    $query= "SELECT COUNT(*) FROM `roles`";
    $stmt=$this->db->query($query);
    return $stmt->fetchColumn() > 0 ;
}
private function adminUserExist(){
    $query= "SELECT COUNT(*) FROM `users` WHERE `username`='Admin' AND `is_admin` = 1";
    $stmt=$this->db->query($query);
    return $stmt->fetchColumn() > 0 ;
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

$otpTableQuery="CREATE TABLE IF NOT EXISTS `otp_codes` (
    `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
    `user_id` int(11) NOT NULL,
    `otp_code` int(11) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
)";


$userStatesQuery="CREATE TABLE IF NOT EXISTS `user_states` (
    `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
    `chat_id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `state` VARCHAR(233) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   UNIQUE INDEX(chat_id)
)";


$userTelegramQuery="CREATE TABLE IF NOT EXISTS `user_telegrams` (
    `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
    `user_id` int(11) NOT NULL,
    `telegram_chat_id` VARCHAR(255) NOT NULL,
    `telegram_username` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
)";


try{
   $this->db->exec($roleTableQuery);
   $this->db->exec($userTableQuery);
   $this->db->exec($otpTableQuery);
   $this->db->exec($userStatesQuery);
   $this->db->exec($userTelegramQuery);

   // вставка записей в таблицу roles

   if(!$this->rolesExist()){
    $insertRolesQuery = "INSERT INTO `roles` (`role_name`, `role_description`) VALUES
    ('Subscriber', 'Может только читать статьи и оставлять комментарии, но не имеет права создавать свой контент или управлять сайтом.'),
    ('Editor', 'Доступ к управлению и публикации статей, страниц и других контентных материалов на сайте. Редактор также может управлять комментариями и разрешать или запрещать их публикацию.'),
    ('Author', 'Может создавать и публиковать собственные статьи, но не имеет возможности управлять контентом других пользователей.'),
    ('Contributor', 'Может создавать свои собственные статьи, но они не могут быть опубликованы до одобрения администратором или редактором.'),
    ('Administrator', 'Полный доступ ко всем функциям сайта, включая управление пользователями, плагинами а также создание и публикация статей.');";
    $this->db->exec($insertRolesQuery);
   }

 // Вставка записи в таблицу users
 if (!$this->adminUserExist()) {
    $insertAdminQuery = "INSERT INTO `users` (`username`, `email`, `password`, `is_admin`, `role`) VALUES
    ('Admin', 'admin@gmail.com', '\$2y\$10\$dySccJEuCWDzywOgSoSU.eafBWHBXWp0/Nd7gohVz1z6mw1QzbEjW', 1, 
    (SELECT `id` FROM `roles` WHERE `role_name` = 'Administrator'));";
    $this->db->exec($insertAdminQuery);
}

    return true;
   }catch(\PDOException $e){
    return false;
   }

}

    public function readAll(){
        try{
$stmt=$this->db-> query("SELECT * FROM  `users`");
        

        $users=[];

        while($row=$stmt->fetch(\PDO::FETCH_ASSOC)){
            $users[]=$row;
        }
        return $users;
    }catch(\PDOException $e){
        return false;
       };
    //    Database::tte( $stmt);
    }

    public function create($data){
    //    tte( $data); 
        $username=$data['username'];
        $email=$data['email'];
        $password=password_hash($data['password'], PASSWORD_DEFAULT);
        $role=$data['role'];
        $created_at=date('Y-m-d H:i:s');
//    tte($_POST['password'].' :'.$password);

       $query=" INSERT INTO users (username,email,password,role,created_at) VALUES (?,?,?,?,?)";
      
    // $query = "INSERT INTO users (username, email, password, role, created_at) 
    // VALUES ('$username', '$email', '$password', '$role', '$created_at')";
    // $this->db->exec($query);


      try{
       
        $stmt=$this->db->prepare($query);
        
         $stmt->execute([$username ,$email, $password,$role,$created_at]);
        //  Database::tte( $stmt);
        echo "Query executed successfully!\n";
        return true;
      }catch(\PDOException $e){
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
   }catch(\PDOException $e){
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
           }catch(\PDOException $e){
            return false;
           }
    }

    public function writeOTPCodeByUserId($data){
        //    tte( $data); 
            $otp=$data['otp_code'];
            $user_id=$data['user_id'];
            $created_at=date('Y-m-d H:i:s');
    
           $query=" INSERT INTO otp_codes (otp_code,user_id,created_at) VALUES (?,?,?)";
    
          try{
           
            $stmt=$this->db->prepare($query);
            
             $stmt->execute([$otp ,$user_id,$created_at]);
            //  Database::tte( $stmt);
            return true;
          }catch(\PDOException $e){
            return false;
           } 
        }

        
        public function getLastOtpByUserId($id){
            $query="SELECT * FROM otp_codes WHERE user_id = ? ORDER BY  created_at DESC LIMIT 1";
        
            try{
                $stmt=$this->db->prepare($query);
                $stmt->execute([$id]);
                $role= $stmt->fetch(\PDO::FETCH_ASSOC);
                return $role ? $role : false;
               }catch(\PDOException $e){
                return false;
               }
        }

        // получение состояния пользователя для авторизации через телеграм
        public function getUsersState($chatId){
            $query="SELECT * FROM use_states WHERE chat_id = ? ";
        
            try{
                $stmt=$this->db->prepare($query);
                $stmt->execute([$chatId]);
                $role= $stmt->fetch(\PDO::FETCH_ASSOC);
                return $role ? $role : false;
               }catch(\PDOException $e){
                return false;
               }
        }

// запись  состояния пользователя для авторизации через телеграм

public function setUserState($chatId, $state, $userId= null){
    //    tte( $data); 
    $query = "INSERT INTO user_states (chat_id, state, user_id) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE state = ?, user_id = ?";

      try{
       
        $stmt = $this->db->prepare($query);
        $stmt->execute([$chatId, $state, $userId, $state, $userId]);
    }catch (\PDOException $e) {
        return false;
    }
    }
    // создание пользователя Телеграм + miniCRM
public function createUserTelegram($user_id, $chatId, $username){
    //    tte( $data); 
    $query = "INSERT INTO user_telegrams (user_id, telegram_chat_id, telegram_username) VALUES (?, ?, ?)";

      try{
       
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$user_id, $chatId, $username]);
    }catch (\PDOException $e) {
        return false;
    }
}
    

// Получение пользователя по email
public function getUserByEmail($email) {
    $query = "SELECT * FROM users WHERE email = ?";
    try {
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        return false;
    }
}

// получение информации о пользователе по его ID и введенному в телеграм ОТП паролю
public function getOtpInfoByUserIdAndCode($user_id, $otpCode) {
    $query = "SELECT * FROM otp_codes WHERE user_id = ? AND otp_code =? AND
     created_at >= DATE_SUB(NOW(),INTERVAL 60 MINUTE)";
    try {
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $otpCode]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        return false;
    }
}


    public function delete($id){

        $query="DELETE FROM users WHERE id = ?";

            try{
                $stmt=$this->db->prepare($query);
                $stmt->execute([$id]);
                return true;
               }catch(\PDOException $e){
                return false;
               }        
    }

    
}