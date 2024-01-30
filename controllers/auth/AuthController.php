<?php 
//  require_once 'app/models/AuthUser.php';
namespace controllers\auth;

use models\AuthUser;
class AuthController{

    public function register(){

  include 'app/views/auth/register.php';
    }

    public function store(){
     
      //  Database::tte($_POST);
if(isset($_POST['username'])&&isset($_POST['password'])
 &&isset($_POST['confirm_password'])&&isset($_POST['email'])){

$username=trim($_POST['username']);
$email=trim($_POST['email']);
$password=trim($_POST['password']);
$confirm_password=trim($_POST['confirm_password']);
// $role = 1;,$role
if(empty($username)||empty($email)||empty($password)||empty($confirm_password)){
    echo "All fields are required";
    return;
}

if($password!==$confirm_password){
  echo "Password do not match!!!";
  return;
}
$password=password_hash($password, PASSWORD_DEFAULT);
$userModel = new AuthUser();
$userModel->register($username,$email,$password);


// Database::tte($_POST['password'].' :'.$password);
}
$path= '/'.APP_BASE_PATH.'/auth/login';
header("Location: $path");
    }

    public function login(){
      
      include 'app/views/auth/login.php';
    }


    public function authenticate(){
   $authModel=new AuthUser();

   if(isset($_POST['email'])&&isset($_POST['password'])){

    $email=trim($_POST['email']);
    $password=trim($_POST['password']);
    $remember=isset($_POST['remember']) ? $_POST['remember'] : '';

    $user = $authModel->findByEmail($email);
   
   }
  //  Database::tte($user['password']);
  //  Database::tte($user['password'].' :'.$password);
   if($user&& password_verify($password, $user['password'])){
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role']= $user['role'];
    $_SESSION['user_name']= $user['username'];

     if($remember=='on'){

        setcookie('user_email' ,$email, time() + (7 * 24 * 60 * 60),'/');
        setcookie('user_password' ,$password, time() + (7*24*60*60),'/');
     }

     $path= '/'.APP_BASE_PATH.'/';
     header("Location: $path");
   }else{
    echo "Invalid email or password!!!";
   }
    }

    public function logout(){
        session_start();
        session_unset();
        session_destroy();

       $path= '/'.APP_BASE_PATH;
       header("Location: $path");
    }

}