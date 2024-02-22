<?php  

namespace models\todo\category;

use models\Database;

class CategoryModel{

    private $db;
    private $userID;
    public function __construct(){
        $this->db=Database::getInstance()->getConnection();
        $this->userID=isset($_SESSION['user_id'] ) ? $_SESSION['user_id'] : null;
        try{
        $result=$this->db->query("SELECT 1 FROM `todo_category` LIMIT 1");    
        }catch(\PDOException $e){
        $this->createTable();
        }
    }

public function createTable(){
$todoTableQuery="CREATE TABLE IF NOT EXISTS `todo_category` (
        `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT,
        `usability` TINYINT DEFAULT 1,
        `user` INT NOT NULL,
        FOREIGN KEY(user) REFERENCES users(id) ON DELETE CASCADE
)";


try{
   $this->db->exec($todoTableQuery);
    return true;
   }catch(\PDOException $e){
    return false;
   }

}

public function getAllCategories(){
    $query="SELECT * FROM  `todo_category` WHERE user = ?";
    try{
   $stmt=$this->db-> prepare( $query);
   $stmt->execute([$this->userID]);
   $categories=$stmt->fetchAll(\PDO::FETCH_ASSOC);
//    Database::tte($roles);
    return $categories;
}catch(\PDOException $e){
    return false;
   };
}

// для использования внутри создания task
public function getAllCategoriesWithUsability(){
    try{
        $stmt = $this->db->query("SELECT * FROM todo_category");
        $stmt = $this->db->prepare("SELECT * FROM todo_category WHERE user = ? AND usability = 1");
        $stmt->execute([$this->userID]);
        $todo_category = [];
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $todo_category[] = $row;
        }
        return $todo_category;
    }catch(\PDOException $e){
        return false;
    }
}
public function getCategoryById($id){
    $query="SELECT * FROM todo_category WHERE id = ?";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$id]);
        $role= $stmt->fetch(\PDO::FETCH_ASSOC);
        return $role ? $role : false;
       }catch(\PDOException $e){
        return false;
       }
}
    public function createCategory($title,$description,$user_id){

        $query = "INSERT INTO todo_category (title,description,user) VALUES (?,?,?)";
        try{
        
     $stmt=$this->db->prepare($query);
     $stmt->execute([$title,$description,$user_id]);
        return true;
    }catch(\PDOException $e){
        return false;
       }
    }


    public function updateCategory($id,$title,$description,$usability){
  $query="UPDATE `todo_category` SET `title` = ?, `description` = ? , `usability` = ? WHERE `id` = ? ";

  try{
    $stmt=$this->db->prepare($query);
    $stmt->execute([$title,$description,$usability,$id]);
    // Database::tte( $user);
    return true;
   }catch(\PDOException $e){
    return false;
   }
        }



        public function deleteCategory($id){
      $query= "DELETE FROM todo_category WHERE id = ?";

      try{
        $stmt=$this->db->prepare($query);
    $stmt->execute([$id]);
    return true;
   }catch(\PDOException $e){
    return false;
   }
      }
 }