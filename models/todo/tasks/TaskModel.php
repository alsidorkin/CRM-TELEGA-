<?php  

namespace models\todo\tasks;

use models\Database;

class TaskModel{

    private $db;
   

    public function __construct(){
        $this->db=Database::getInstance()->getConnection();
       
        try{
        $result=$this->db->query("SELECT 1 FROM `todo_task` LIMIT 1");    
        }catch(\PDOException $e){
        $this->createTable();
        }
    }

public function createTable(){
$todoTableQuery="CREATE TABLE IF NOT EXISTS `todo_task` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `category_id` INT  NULL,
    `status` ENUM('new', 'in_progress', 'completed', 'on_hold', 'cancelled') NOT NULL,
    `priority` ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
    `assigned_to` INT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `finish_date` DATETIME,
    `completed_at` DATETIME,
    `reminder_at` DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES todo_category(id) ON DELETE SET NULL
    -- FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
)";
$remindersTable="CREATE TABLE IF NOT EXISTS `todo_reminders` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL,
    `task_id` INT(11) NOT NULL,
    `reminder_at` DATETIME,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES todo_task(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    -- FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
)";


try{
   $this->db->exec($todoTableQuery);
   $this->db->exec($remindersTable);
 
    return true;
   }catch(\PDOException $e){
    echo "Ошибка при создании таблицы: " . $e->getMessage();
    return false;
   }

}

public function getAllTasks(){
    $query="SELECT * FROM  `todo_task`";
    try{
   $stmt=$this->db-> prepare( $query);
   $stmt->execute();
   $tasks=$stmt->fetchAll(\PDO::FETCH_ASSOC);
//    tte($tasks);
    return $tasks;
}catch(\PDOException $e){
    return false;
   };
}
public function getAllTasksByIdUser($user_id){
    $query="SELECT * FROM  `todo_task` WHERE finish_date > NOW() AND  user_id=:user_id 
    AND status != 'completed' 
    ORDER BY ABS(TIMESTAMPDIFF(SECOND,NOW(),finish_date))";
    try{
   $stmt=$this->db-> prepare( $query);
   $stmt->execute(['user_id'=>$user_id]);
   $tasks=$stmt->fetchAll(\PDO::FETCH_ASSOC);
//    tte($tasks);
    return $tasks;
}catch(\PDOException $e){
    return false;
   };
}
public function getAllCompletedTasksByIdUser( $user_id){
    $query="SELECT * FROM  `todo_task` WHERE user_id=:user_id 
    AND status = 'completed' 
    ORDER BY ABS(TIMESTAMPDIFF(SECOND,NOW(),finish_date))";
    try{
   $stmt=$this->db-> prepare( $query);
   $stmt->execute(['user_id'=>$user_id]);
   $tasks=$stmt->fetchAll(\PDO::FETCH_ASSOC);
//    tte($tasks);
    return $tasks;
}catch(\PDOException $e){
    return false;
   };
}
public function getAllExpiredTasksByIdUser( $user_id){
    $query="SELECT * FROM  `todo_task` WHERE finish_date < NOW() AND user_id=:user_id 
    
    ORDER BY ABS(TIMESTAMPDIFF(SECOND,NOW(),finish_date))";
    try{
   $stmt=$this->db-> prepare( $query);
   $stmt->execute(['user_id'=>$user_id]);
   $tasks=$stmt->fetchAll(\PDO::FETCH_ASSOC);
    // tte($tasks);
    return $tasks;
}catch(\PDOException $e){
    return false;
   };
}

public function getTaskById($id){
    $query="SELECT * FROM todo_task WHERE id = ?";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$id]);
        $task= $stmt->fetch(\PDO::FETCH_ASSOC);
        return $task ? $task : false;
       }catch(\PDOException $e){
        return false;
       }
}
public function getTaskByIdAndByIdUser($id_task, $id_user){
    $query="SELECT * FROM todo_task WHERE id = ? AND user_id = ?";

    try{
        $stmt=$this->db->prepare($query);
        $stmt->execute([$id_task, $id_user]);
        $task= $stmt->fetch(\PDO::FETCH_ASSOC);
        return $task ? $task : [];
       }catch(\PDOException $e){
        return false;
       }
}
    public function createTasks($data){
        $user_id=$data['user_id'];
        $title=$data['title'];
        $description=$data['description'];
        $category_id=$data['category_id'];
        $status=$data['status'];
        $priority=$data['priority'];
        $finish_date=$data['finish_date'];
        $reminder_at = $data['reminder_at'];

        $query = "INSERT INTO todo_task (user_id,title,description,category_id,status,priority,
        finish_date,reminder_at) VALUES (?,?,?,?,?,?,?,?)";
        try{
        
     $stmt=$this->db->prepare($query);
     $stmt->execute([$user_id,$title,$description,$category_id,$status,$priority,$finish_date, $reminder_at]);
        return true;
    }catch(\PDOException $e){
        return false;
       }
    }
    // $data['title'] = trim($_POST['title']);
    // $data['category_id'] = trim($_POST['category_id']);
    // $data['finish_date'] = trim($_POST['finish_date']);
    // $data['status'] = 'new';
    // $data['priority'] = 'low';
    // $data['description'] =  trim($_POST['description']);

    public function updateTask($data){
        // tte($data);
  $query="UPDATE `todo_task` SET `title` = ?,`category_id` = ?,`finish_date` = ?,`status` = ?,
  `priority` = ?, `description` = ?, `reminder_at`=?  WHERE `id` = ? ";

  try{
    $stmt=$this->db->prepare($query);
    $stmt->execute([$data['title'],$data['category_id'],$data['finish_date'],$data['status'],
    $data['priority'],$data['description'],$data['reminder_at'],$data['id']]);
    return true;
   }catch(\PDOException $e){
    return false;
   }
        }


        public function updateTaskStatus($id, $status, $datetime){

            $query = "UPDATE todo_task SET status = :status";
    
            try{
                if($datetime !== null){
                    $query .= ", completed_at = :completed_at";
                }else{
                    $query .= ", completed_at = NULL";
                }
    
                $query .= " WHERE id = :id";
    
                $stmt = $this->db->prepare($query);
    
                $params = [':status' => $status, ':id' => $id];
    
                if($datetime !== null){
                    $params[':completed_at'] = $datetime;
                }
    
                $stmt->execute($params);
                return $stmt->rowCount() > 0;
            }catch(\PDOException $e){
                return false;
            } 
        }

        
        public function getTasksByTagId($tag_id, $user_id){
            $query="SELECT * FROM todo_task
            JOIN task_tags ON todo_task.id =task_tags.task_id 
            WHERE task_tags.tag_id =?
            AND  todo_task.user_id = ?
            ORDER BY ABS(TIMESTAMPDIFF(SECOND,NOW(),finish_date))";
        
            try{
                $stmt=$this->db->prepare($query);
                $stmt->execute([$tag_id, $user_id]);
                $task= $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $task ? $task : false;
               }catch(\PDOException $e){
                return false;
               }
        }
        public function deleteTask($id){
            // tte($id); 
      $query= "DELETE FROM todo_task WHERE id = ?";

      try{
        $stmt=$this->db->prepare($query);
        $result=$stmt->execute([$id]);
        if ($result) {
            echo "Task with ID $id deleted successfully.";
        } else {
            echo "Failed to delete task with ID $id.";
        }
         return true;
   }catch(\PDOException $e){
    return false;
   }
      }

      public function getTasksCountAndStatusByUserId($user_id){
        $query="SELECT 
        COUNT(*) AS all_tasks,
        SUM(status='completed') AS completed,
        SUM(status!='completed' AND finish_date < NOW() OR status !='completed' AND finish_date IS NULL) AS expired,
        SUM(status!='completed' AND finish_date > NOW() OR status !='completed' AND finish_date IS NULL) AS opened
        FROM 
        todo_task
           WHERE user_id= :user_id ";
    
        try{
            $stmt=$this->db->prepare($query);
            $stmt->execute([$user_id]);
            $task= $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $task ? $task : [];
           }catch(\PDOException $e){
            return [];
           }
    }

 }