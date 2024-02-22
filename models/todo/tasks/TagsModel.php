<?php  

namespace models\todo\tasks;

use models\Database;

class TagsModel{

    private $db;
   

    public function __construct(){
        $this->db=Database::getInstance()->getConnection();
       
        try{
        $result=$this->db->query("SELECT 1 FROM `tags` LIMIT 1");    
        }catch(\PDOException $e){
        $this->createTables();
        }
    }

// public function createTables(){
// $todoTableQuery="CREATE TABLE IF NOT EXISTS `tags` (
//     `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     `user_id` INT,
//     `name` VARCHAR(255) NOT NULL,
//     FOREIGN KEY (user_id) REFERENCES users(id));

//     CREATE TABLE IF NOT EXISTS `task_tags` (
//     `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     `task_id` INT NOT NULL,
//     `tag_id` INT NOT NULL,
//     FOREIGN KEY (task_id) REFERENCES todo_task(id),
//     FOREIGN KEY (tag_id) REFERENCES tags(id)
// )";


// try{
//    $this->db->exec($todoTableQuery);
//     return true;
//    }catch(\PDOException $e){
//     return false;
//    }

// }



public function createTables(){
    $todoTableQuery1 = "CREATE TABLE IF NOT EXISTS `tags` (
        `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT,
        `name` VARCHAR(255) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";

    $todoTableQuery2 = "CREATE TABLE IF NOT EXISTS `task_tags` (
        `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `task_id` INT NOT NULL,
        `tag_id` INT NOT NULL,
        FOREIGN KEY (task_id) REFERENCES todo_task(id),
        FOREIGN KEY (tag_id) REFERENCES tags(id)
    )";

    try {
        $this->db->exec($todoTableQuery1);
        $this->db->exec($todoTableQuery2);
        return true;
    } catch(\PDOException $e) {
        return false;
    }
}




// public function getAllTasks(){
//     $query="SELECT * FROM  `todo_task`";
//     try{
//    $stmt=$this->db-> prepare( $query);
//    $stmt->execute();
//    $tasks=$stmt->fetchAll(\PDO::FETCH_ASSOC);
// //    tte($tasks);
//     return $tasks;
// }catch(\PDOException $e){
//     return false;
//    };
// }
public function getTagsByTaskId($task_id){
    $query="SELECT tags.* FROM tags 
    JOIN task_tags ON tags.id = task_tags.tag_id
    WHERE task_tags.task_id = ?";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$task_id]);
        $tags=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        // tte($tags);
        return $tags ? $tags : '';
       }catch(\PDOException $e){
        return false;
       }
}

function removeAllTaskTags($task_id){
    $query="DELETE FROM task_tags WHERE task_id = :task_id";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute(['task_id' => $task_id]);
       }catch(\PDOException $e){
        return false;
       }

}

function getTagByNameAndUserId($tag_name,$user_id){
    $query="SELECT * FROM tags WHERE name= ? AND user_id = ?";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$tag_name,$user_id]);
         return $stmt->fetch(\PDO::FETCH_ASSOC);
       }catch(\PDOException $e){
        return false;
       }
}
function getTagNameById($tag_id){
    $query="SELECT name FROM tags WHERE id = ?";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$tag_id]);
        $tag=$stmt->fetch(\PDO::FETCH_ASSOC);
         return $tag ? $tag['name'] : '';
       }catch(\PDOException $e){
        return false;
       }
}

function addTag($tag_name,$user_id){
    $tag_name=strtolower($tag_name);
    $query="INSERT INTO tags (name,user_id) VALUES (LOWER(?),?)";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$tag_name,$user_id]);
        return $this-> db -> lastInsertId();
       }catch(\PDOException $e){
        return false;
       }
}


function addTaskTag($task_id,$tag_id){
    $query="INSERT INTO task_tags (task_id,tag_id) VALUES (?, ?)";
    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$task_id,$tag_id]);
        return true;
       }catch(\PDOException $e){
        return false;
       }
}

function removeUnusedTag($tag_id){

    $query="SELECT COUNT(*) FROM task_tags WHERE tag_id = ?";
    $stmt=$this->db->prepare($query);
    $stmt->execute([$tag_id]);
    $count=$stmt->fetch(\PDO::FETCH_ASSOC)['COUNT(*)'];
    try{
        if($count==0){
            $query="DELETE FROM tags WHERE id = ?";
            $stmt=$this->db->prepare($query);
            $stmt->execute([$tag_id]);
        }
       
       }catch(\PDOException $e){
        return false;
       }
}


//     public function createTasks($data){
//         $user_id=$data['user_id'];
//         $title=$data['title'];
//         $description=$data['description'];
//         $category_id=$data['category_id'];
//         $status=$data['status'];
//         $priority=$data['priority'];
//         $finish_date=$data['finish_date'];

//         $query = "INSERT INTO todo_task (user_id,title,description,category_id,status,priority,
//         finish_date) VALUES (?,?,?,?,?,?,?)";
//         try{
        
//      $stmt=$this->db->prepare($query);
//      $stmt->execute([$user_id,$title,$description,$category_id,$status,$priority,$finish_date]);
//         return true;
//     }catch(\PDOException $e){
//         return false;
//        }
//     }


//     public function updateCategory($id,$title,$description,$usability){
//   $query="UPDATE `todo_category` SET `title` = ?, `description` = ? , `usability` = ? WHERE `id` = ? ";

//   try{
//     $stmt=$this->db->prepare($query);
//     $stmt->execute([$title,$description,$usability,$id]);
//     // Database::tte( $user);
//     return true;
//    }catch(\PDOException $e){
//     return false;
//    }
//         }



//         public function deleteCategory($id){
//       $query= "DELETE FROM todo_category WHERE id = ?";

//       try{
//         $stmt=$this->db->prepare($query);
//     $stmt->execute([$id]);
//     return true;
//    }catch(\PDOException $e){
//     return false;
//    }
//       }
 }