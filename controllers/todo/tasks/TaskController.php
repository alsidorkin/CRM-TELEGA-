<?php 

 namespace controllers\todo\tasks;

 use models\todo\tasks\TaskModel;
 use models\todo\tasks\TagsModel;
 use models\todo\category\CategoryModel;
 use models\Check;
 use models\roles\Role;
 
class TaskController{
  private $check;
  private $tagsModel;

  public function __construct(){
    $userRole=isset($_SESSION['user_role'] ) ? $_SESSION['user_role'] : null;
    $this->check=new Check($userRole); 
    $this->tagsModel=new TagsModel(); 
  }
    public function index(){
          $this->check->requirePermission(); 
          $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
          // tt($user_id);
            $todoTaskModel = new TaskModel(); 
            $tasks=$todoTaskModel->getAllTasksByIdUser($user_id); 
            //  tt($tasks);
            $categoryModel = new CategoryModel();
             // получение списка тегов для каждой записи в массиве 

  foreach($tasks as &$task){
    //  tt($task['id']);
    $task['tags']= $this->tagsModel->getTagsByTaskId($task['id']);
    // tt($task['tags']);
    $task['tags'] = $task['tags'] ? $task['tags'] : [];
    // tte($task['tags']);
    $task['category']= $categoryModel->getCategoryById($task['category_id']);
  } 
 
  include 'app/views/todo/tasks/index.php';
    }
    public function completed(){
          $this->check->requirePermission(); 
          $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $todoTaskModel = new TaskModel(); 
            $comletedTasks=$todoTaskModel->getAllCompletedTasksByIdUser($user_id);  
            // tt($comletedTasks['category_id']);
          $categoryModel = new CategoryModel();
             // получение списка тегов для каждой записи в массиве 
          
  foreach($comletedTasks as &$task){
    $task['tags']= $this->tagsModel->getTagsByTaskId($task['id']);

    $task['category']= $categoryModel->getCategoryById($task['category_id']);
  }    
 
         include 'app/views/todo/tasks/completed.php';
    }
    public function expired(){
          $this->check->requirePermission(); 
          $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $todoTaskModel = new TaskModel(); 
            $expiredTasks=$todoTaskModel->getAllExpiredTasksByIdUser( $user_id);   
            $categoryModel = new CategoryModel();

            // получение списка тегов для каждой записи в массиве 
 foreach($expiredTasks as &$task){
   $task['tags']= $this->tagsModel->getTagsByTaskId($task['id']);
   $task['tags'] = $task['tags'] ? $task['tags'] : [];
   $task['category']= $categoryModel->getCategoryById($task['category_id']);
 } 
 
         include 'app/views/todo/tasks/expired.php';
    }

    
    public function create(){
       $this->check->requirePermission(); 
       $todoCategoryModel = new CategoryModel();
       $categories = $todoCategoryModel->getAllCategoriesWithUsability();
      //  tte($categories);
       include 'app/views/todo/tasks/create.php';
    }


    public function store(){

      $this->check->requirePermission();

      if(isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])){
          $data['title'] = trim(htmlspecialchars($_POST['title']));
          $data['category_id'] = trim(htmlspecialchars($_POST['category_id']));
          $data['finish_date'] = trim(htmlspecialchars($_POST['finish_date']));
          $data['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
          $data['status'] = 'new';
          $data['priority'] = 'low';

          $taskModel = new TaskModel();
          $taskModel->createTasks($data);

      }
      $path = '/todo/tasks';
      header("Location: $path");
  }

    public function edit($params){
    
       $this->check->requirePermission(); 

      $id=$params['id'];
      $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      $task_id = isset($id) ? intval($id) : 0;
      $taskModel = new TaskModel();
      $task=$taskModel->getTaskById($id);
      // tte( $task);

      if(!$task || $task['user_id'] !=$user_id){

        http_response_code(404);
        include 'app/views/errors/404.php';
          return;
       }
      $todoCategoryModel = new CategoryModel();
      $categories = $todoCategoryModel->getAllCategoriesWithUsability();  

      if(!$task){
        echo 'task not found!!!';
        return;
      }
       
      $tags = $this->tagsModel->getTagsByTaskId($task['id']);
      // tte( $tags);
      include 'app/views/todo/tasks/edit.php';
    }


    public function task($params){
    
      $this->check->requirePermission(); 

     $id=$params['id'];
     $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
     $task_id = isset($id) ? intval($id) : 0;
     $taskModel = new TaskModel();
     $task=$taskModel->getTaskByIdAndByIdUser($id,$user_id);
     // tte( $task);
       if(!$task || $task['user_id'] !=$user_id){

        http_response_code(404);
        include 'app/views/errors/404.php';
          return;
       }


     $todoCategoryModel = new CategoryModel();
     $category = $todoCategoryModel->getCategoryById($task['category_id']);  

     if(!$task){
       echo 'task not found!!!';
       return;
     }
      
     $tags = $this->tagsModel->getTagsByTaskId($task['id']);
     // tte( $tags);
     include 'app/views/todo/tasks/task.php';
   }


    public function update(){
      //  tt($params);
      //  tte($_POST);
     
      $this->check->requirePermission(); 

      if(isset($_POST['title'])&& isset($_POST['id']) && isset($_POST['category_id']) && isset($_POST['finish_date'])){
        $data['title'] = trim(htmlspecialchars($_POST['title']));
        $data['id'] = $_POST['id'];
        $data['category_id'] = trim(htmlspecialchars($_POST['category_id']));
        $data['finish_date'] = trim(htmlspecialchars($_POST['finish_date']));
        $data['status'] = trim(htmlspecialchars($_POST['status']));
        $data['priority'] = trim(htmlspecialchars($_POST['priority']));
        $data['description'] =  trim(htmlspecialchars($_POST['description']));
        $data['reminder_at']=trim(htmlspecialchars($_POST['reminder_at']));
        $data['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        // обработка даты окончания и напоминания

        $finish_date_value= $data['finish_date'];
        $reminder_at_option=$data['reminder_at'];
        $finish_date= new \DateTime($finish_date_value);

        switch($reminder_at_option){
          case '30_minutes':
            $interval=new \DateInterval('PT30M');
            break;
          case '1_hour':
            $interval=new \DateInterval('PT1H');
            break;
          case '2_hours':
            $interval=new \DateInterval('PT2H');
            break;
          case '12_hours':
            $interval=new \DateInterval('PT12H');
            break;
          case '24_hours':
            $interval=new \DateInterval('P1D');
            break;
          case '7_days':
            $interval=new \DateInterval('P7D');
            break;
        }

        $reminder_at=$finish_date->sub($interval);
        $data['reminder_at'] = $reminder_at->format('Y-m-d\TH:i');

        // обновляем данные по задаче в базе
        $taskModel = new TaskModel();
        $taskModel->updateTask($data); 

        // обработка тегов
        $tags = explode(',',$_POST['tags']);
        $tags = array_map('trim', $tags);

        // получение тегов с базы по задаче которую редактируем

        $oldTags = $this->tagsModel->getTagsByTaskId($data['id']);
      //  tte($oldTags);
        // удаление старых связей между тегами и задачей
        $this -> tagsModel->removeAllTaskTags($data['id']); 

        // добавляем новые теги и связываем с задачей
        foreach($tags as $tag_name){
          $tag=$this -> tagsModel->getTagByNameAndUserId($tag_name,$data['user_id']);  
          // tte($tag);
          if(!$tag){
            $tag_id= $this->tagsModel->addTag($tag_name,$data['user_id']);  
          }else{
            $tag_id = $tag['id']; 
          }
          $this->tagsModel->addTaskTag($data['id'],$tag_id); 
        }
        
        // удаляем неиспользуемые теги
        foreach($oldTags as $oldTag){
          $this->tagsModel->removeUnusedTag($oldTag['id']); 
        }
    }
    // tte($data);
    $path = '/todo/tasks';
    header("Location: $path");
}



public function tasksByTag($params){
  // tt($params);
  $this->check->requirePermission(); 
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
  $categoryModel = new CategoryModel();

 $id=$params['id'];
 $taskModel = new TaskModel();
 $tasksByTag=$taskModel->getTasksByTagId($id,$user_id);
//  tte($tasksByTag);
 $tagsModel = new TagsModel();
 $tagname = $tagsModel->getTagNameById($id);  
//  tt($tagname);
// получение списка тегов для каждой записи в массиве 
foreach($tasksByTag as &$task){
  // tt($task);
  $task['tags']= $this->tagsModel->getTagsByTaskId($task['task_id']);
  $task['category']= $categoryModel->getCategoryById($task['category_id']);
} 

 // tte( $tags);
 include 'app/views/todo/tasks/by_tag.php';
}



public function updateStatus($params){
  // tt($params);
  $this->check->requirePermission(); 
  
$datetime=null;
$status= trim(htmlspecialchars($_POST['status']));

$id=$params['id'];

if($status){
 
  if($status == 'completed'){
    $datetime=date("Y-m-d H:i:s");
  }
  // tte( $status);
  $taskModel = new TaskModel();
  $taskModel->updateTaskStatus($id,$status,$datetime);
  
  $path = '/todo/tasks';
  header("Location: $path");
   }else{
    echo ' Не удалось обновить статус!';
   }
}


    
    public function delete($params){  
      
      $this->check->requirePermission(); 
      $id=$params['id'];
      $todoTaskModel = new TaskModel();
      $todoTaskModel->deleteTask($id);
      $path='/todo/tasks';
       header("Location: $path");
    }

}