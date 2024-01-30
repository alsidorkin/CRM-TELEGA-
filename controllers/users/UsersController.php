<?php 

namespace controllers\users;

use models\roles\Role;
use models\users\User;
use models\Check; 
class UsersController{
  private $check;

  public function __construct(){

    $userRole=isset($_SESSION['user_role'] ) ? $_SESSION['user_role'] : null;
    $this->check=new Check($userRole); 
  }
    public function index(){
      $this->check->requirePermission(); 
  $userModel = new User();
  $users=$userModel->readAll();

  include 'app/views/users/index.php';
    }

    
    public function create(){
      $this->check->requirePermission(); 
  include 'app/views/users/create.php';
    }


    public function store(){
      $this->check->requirePermission(); 
      // Database::tte($_POST);
if(isset($_POST['username'])&&isset($_POST['password'])
 &&isset($_POST['confirm_password'])&&isset($_POST['email'])){

$username= trim($_POST['username']); 
$email= trim($_POST['email']);
$password=trim($_POST['password']);
$confirm_password=trim($_POST['confirm_password']);

// $role= 1;

if($password!==$confirm_password){
  echo "Password do not match!!!";
  return;
}else{

$userModel = new User();
// $config =require __DIR__.'/../../config.php';
$data=[
'username'=>$username,
'email'=> $email,
'password'=>$password,
// 'role'=> $config['start_role']
'role'=> START_ROLE,
];

$userModel->create($data);
}
$path='/'.APP_BASE_PATH.'/users';
header("Location: $path");
}
    }

    public function edit($params){
      // tt($_SESSION);
      $this->check->requirePermission(); 
      // tte($params); 
      $userModel = new User();
      $user=$userModel->read($params['id']);
      
      $roleModel = new Role();
      $roles=$roleModel->getAllRoles();

      include 'app/views/users/edit.php';
    }



    public function update($params){
      $this->check->requirePermission(); 

      if(isset($_POST['role'])){
        $newRole=$_POST['role'];
      }

      if($this->check->isCurrentUserRole($newRole)){
        $path='/'.APP_BASE_PATH.'/auth/logout';
       header("Location: $path");
       exit;
      }
      $userModel = new User();
      $user=$userModel->update($params['id'],$_POST);
    //   if (isset($_POST['email'])) {
    //     $newEmail = $_POST['email'];

    //     // Проверяем, совпадает ли роль текущего пользователя с обновленной ролью
    //     if ($newEmail == $_SESSION['user_email']) {
    //         $path = '/' . APP_BASE_PATH . '/auth/logout';
    //         header("Location: $path");
    //         exit();
    //     }
    // }
      $path='/'.APP_BASE_PATH.'/users';
       header("Location: $path");
    }


    public function delete($params){
      $this->check->requirePermission(); 
      $userModel = new User();
       $userModel->delete($params['id']);
       $path='/'.APP_BASE_PATH.'/users';
        header("Location: $path");
    }
}