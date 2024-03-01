<?php 
namespace controllers\home;

use models\todo\tasks\TaskModel;
use models\users\User;
use models\pages\PageModel;
class HomeController{

  public function __construct(){
    $user=new User();
    $user->createTable();

    $pages =new PageModel();
    if($pages->createTable()){
      // если создалась таблица
      $pages->insertPages();

    }
  }
    
        public function index(){
          $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
          $taskModel=new TaskModel();
          $tasks=$taskModel->getAllTasksByIdUser($user_id);
          $taskJson=json_encode($tasks);

      include 'app/views/index.php';
        }
    
    
    }



