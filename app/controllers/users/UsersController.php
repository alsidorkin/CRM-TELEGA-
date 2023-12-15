<?php 
require_once 'app/models/User.php';

class UsersController{
 
    public function index(){
  $userModel = new User();
  $users=$userModel->readAll();

  include 'app/views/users/index.php';
    }

    
    public function create(){

  include 'app/views/users/create.php';
    }


    public function store(){
     
      // Database::tte($_POST);
if(isset($_POST['username'])&&isset($_POST['password'])
 &&isset($_POST['confirm_password'])&&isset($_POST['email'])){
  // Database::tte($_POST);

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
$config =require __DIR__.'/../../../config.php';
$data=[
'username'=>$username,
'email'=> $email,
'password'=>$password,
'role'=> $config['start_role']
];

$userModel->create($data);
}
header("Location: index.php?page=users");
}
    }

    public function edit(){
      $userModel = new User();
      $user=$userModel->read($_GET['id']);
      
      $roleModel = new Role();
      $roles=$roleModel->getAllRoles();

      include 'app/views/users/edit.php';
    }
    public function update(){
      $userModel = new User();
      $user=$userModel->update($_GET['id'],$_POST);
      
      header("Location: index.php?page=users");
    }
    public function delete(){
      $userModel = new User();
       $userModel->delete($_GET['id']);
       header("Location: index.php?page=users");
    }

}