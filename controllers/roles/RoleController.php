<?php 
// require_once 'app/models/roles/Role.php';

 namespace controllers\roles;

 use models\roles\Role;
 use models\Check;
class RoleController{
  private $check;

  public function __construct(){
    $userRole=isset($_SESSION['user_role'] ) ? $_SESSION['user_role'] : null;
    $this->check=new Check($userRole); 
  }
    public function index(){
       $this->check->requirePermission(); 
  $roleModel = new Role(); 
  $roles=$roleModel->getAllRoles();
  // Database::tte($roles);
  include 'app/views/roles/index.php';
    }

    
    public function create(){
       $this->check->requirePermission(); 
  include 'app/views/roles/create.php';
    }


    public function store(){
       $this->check->requirePermission(); 
      // Database::tte($_POST);
if(isset($_POST['role_name'])&&isset($_POST['role_description'])){
  // Database::tte($_POST);
$role_name= trim(htmlspecialchars($_POST['role_name']));
$role_description= trim(htmlspecialchars($_POST['role_description']));

if(empty($role_name)){
  echo "Role name is required!!!";
  return;
}else{

$roleModel = new Role();
// Database::tte($role_name.$role_description);
$roleModel->createRole($role_name,$role_description);
}
$path='/roles';
header("Location: $path");
}
    }

    public function edit($params){
       $this->check->requirePermission(); 
      $id=$params['id'];
      $roleModel = new Role();
      $role=$roleModel->getRoleById($id);
      // Database::tte($role);
      if(!$role){
        echo 'Role not found!!!';
        return;
      }
      
      include 'app/views/roles/edit.php';
    }


    public function update(){
      $this->check->requirePermission(); 

     if(isset($_POST['id'])&&isset($_POST['role_name'])&&isset($_POST['role_description'])){

$id= trim($_POST['id']);
$role_name= trim(htmlspecialchars($_POST['role_name']));
$role_description= trim(htmlspecialchars($_POST['role_description']));

if(empty($role_name)){
  echo "Role name is required!!!";
  return;
}else{

$roleModel = new Role();

$roleModel->updateRole($id,$role_name,$role_description);
}
$path='/roles';
header("Location: $path");
}
    }

    
    public function delete($params){
      $this->check->requirePermission(); 
      $roleModel = new Role();
      $roleModel->deleteRole($params['id']);
      $path='/roles';
      header("Location: $path");
    }

}