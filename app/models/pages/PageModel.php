
<?php  

class PageModel{

    private $db;

    public function __construct(){
        $this->db=Database::getInstance()->getConnection();
        try{
        $result=$this->db->query("SELECT 1 FROM `pages` LIMIT 1");    
        }catch(PDOException $e){
        $this->createTable();
        }
    }

public function createTable(){
$roleTableQuery="CREATE TABLE IF NOT EXISTS `pages` (
        `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=innoDB DEFAULT CHARSET = utf8mb4";


try{
   $this->db->exec($roleTableQuery);
    return true;
   }catch(PDOException $e){
    return false;
   }

}

public function getAllPages(){
    $query="SELECT * FROM  `pages`";
    try{
   $stmt=$this->db-> prepare( $query);
   $stmt->execute();
   $pages=$stmt->fetchAll(PDO::FETCH_ASSOC);
//    Database::tte($roles);
    return $pages;
}catch(PDOException $e){
    return false;
   };
//    Database::tte( $stmt);
}

public function getPageById($id){
    $query="SELECT * FROM pages WHERE id = ?";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$id]);
        $page= $stmt->fetch(PDO::FETCH_ASSOC);
        return $page ? $page : false;
       }catch(PDOException $e){
        return false;
       }
}
    public function createPage($title,$slug){
        // Database::tte("createRole".$role_name.$role_description);

        $query = "INSERT INTO pages (title,slug) VALUES (?,?)";
        try{
        
     $stmt=$this->db->prepare($query);
     $stmt->execute([$title,$slug]);
        return true;
    }catch(PDOException $e){
        return false;
       }
    //    Database::tte( $stmt);
    }


    public function updatePage($id,$title,$slug){
        // Database::tte($id.$title.$slug);
  $query="UPDATE `pages` SET `title` = ?, `slug` = ? WHERE `id` = ? ";
//   Database::tte($query);
  try{
    $stmt=$this->db->prepare($query);
    $stmt->execute([$title,$slug,$id]);
    // Database::tte( $user);
    return true;
   }catch(PDOException $e){
    return false;
   }
        }



        public function deletePage($id){
      $query= "DELETE FROM pages WHERE id = ?";

      try{
        $stmt=$this->db->prepare($query);
         $stmt->execute([$id]);
    // Database::tte( $user);
                return true;
            }catch(PDOException $e){
                return false;
            }
      }
 }