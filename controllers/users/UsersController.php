<?php 

namespace controllers\users;

use models\roles\Role;
use models\users\User;
use models\Check; 
class UsersController{
  private $check;
  private $userId;

  public function __construct(){

    $userRole=isset($_SESSION['user_role'] ) ? $_SESSION['user_role'] : null;
    $this->userId=isset($_SESSION['user_id'] ) ? $_SESSION['user_id'] : null;
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
      // $this->check->requirePermission(); 
    // tte($_POST);
if(isset($_POST['username'])&&isset($_POST['password'])
 &&isset($_POST['confirm_password'])&&isset($_POST['email'])){

$username= trim(htmlspecialchars($_POST['username'])); 
$email= trim(htmlspecialchars($_POST['email']));
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
'role'=> 1,
];

$userModel->create($data);
}
$path='/users';
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
        $newRole=trim(htmlspecialchars($_POST['role']));
      }

      if($this->check->isCurrentUserRole($newRole)){
        $path='/auth/logout';
       header("Location: $path");
       exit;
      }
      $userModel = new User();
      $user=$userModel->update($params['id'],$_POST);
    //   if (isset($_POST['email'])) {
        // $newEmail = trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'));

    //     // Проверяем, совпадает ли роль текущего пользователя с обновленной ролью
    //     if ($newEmail == $_SESSION['user_email']) {
    //         $path = '/' . APP_BASE_PATH . '/auth/logout';
    //         header("Location: $path");
    //         exit();
    //     }
    // }
      $path='/users';
       header("Location: $path");
    }


    public function profile(){
      $this->check->requirePermission(); 

      $user_id=$this->userId;
     
      $userModel = new User();
      $user=$userModel->read($user_id);
      $role_id=$user['role'];
      // tt($user);

      $roleModel = new Role();
      $role=$roleModel->getRoleById($role_id);

     $otp=generationOTP();

     $otpLastStr= $userModel->getLastOtpByUserId($user_id);
      // tte($otpLastStr);

    if($otpLastStr){
      $otpCreated =new \DateTime($otpLastStr['created_at']);
      $nowTime =new \DateTime();// текущая дата и время
      $interval=$otpCreated->diff($nowTime);// разница между временем создания otp и текущим временем

      $secondsDifference=$interval-> days * 24 * 60 * 60;
      $secondsDifference+=$interval-> h* 60 * 60;
      $secondsDifference+=$interval->i * 60;
      $secondsDifference+=$interval->s;

      // tte($secondsDifference);

      if($secondsDifference > 3600){
        $otp=generationOTP();
        $visible=true;
      }else{
        $otp=$otpLastStr['otp_code'];
        $visible=false;
      }
    }else{
      $otp=generationOTP();
        $visible=true;
    }
      include 'app/views/users/profile.php';
    }

    public function otpstore(){
      $this->check->requirePermission(); 
    //  tte($_POST);
if(isset($_POST['otp'])&&isset($_POST['user_id'])){

$otp= trim(htmlspecialchars($_POST['otp'])); 
$user_id= trim(htmlspecialchars($_POST['user_id']));

$userModel = new User();
$data=[
'otp_code'=>$otp,
'user_id'=> $user_id,
];

$userModel->writeOTPCodeByUserId($data);
}
$path='/users/profile';
header("Location: $path");
}
  


    public function delete($params){
      $this->check->requirePermission(); 
      $userModel = new User();
       $userModel->delete($params['id']);
       $path='/users';
        header("Location: $path");
    }
}