<?php 
// require_once 'app/models/roles/Role.php';

 namespace controllers\todo\category;

 use models\todo\category\CategoryModel;
 use models\Check;
 use models\roles\Role;
 
class CategoryController{
  private $check;

  public function __construct(){
    $userRole=isset($_SESSION['user_role'] ) ? $_SESSION['user_role'] : null;
    $this->check=new Check($userRole); 
  }
    public function index(){
       $this->check->requirePermission(); 
  $todoCategoryModel = new CategoryModel(); 
  $categories=$todoCategoryModel->getAllCategories();  
  // Database::tte($roles);
  include 'app/views/todo/category/index.php';
    }

    
    public function create(){
       $this->check->requirePermission(); 
  include 'app/views/todo/category/create.php';
    }


    public function store(){
       $this->check->requirePermission(); 
      // Database::tte($_POST);
if(isset($_POST['title'])&&isset($_POST['description'])){
  // Database::tte($_POST);
$title= trim($_POST['title']);
$description= trim($_POST['description']);
$user_id=isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if(empty($title)||empty($description)){
  echo "Title and Description are required!!!";
  return;
}else{

$todoCategoryModel = new CategoryModel();
$todoCategoryModel->createCategory($title,$description,$user_id);
}
$path='/'.APP_BASE_PATH.'/todo/category';
header("Location: $path");
}
    }

    public function edit($params){
    
       $this->check->requirePermission(); 

      $id=$params['id'];
     $todoCategoryModel = new CategoryModel();
      $category=$todoCategoryModel->getCategoryById($id);
      // Database::tte($role);
      if(!$category){
        echo 'category not found!!!';
        return;
      }
      
      include 'app/views/todo/category/edit.php';
    }


    public function update(){
      // tte($_POST);
     
      $this->check->requirePermission(); 

     if(isset($_POST['id'])&&isset($_POST['title'])&&isset($_POST['description'])){

$id= trim($_POST['id']);
$title= trim($_POST['title']);
$description= trim($_POST['description']);
$usability=isset($_POST['usability']) ? isset($_POST['usability']) : 0;
if(empty($title)||empty($description)){
  echo "Title and description are required!!!";
  return;
}else{

  $todoCategoryModel = new CategoryModel();
  $todoCategoryModel->updateCategory($id,$title,$description,$usability);
}
$path='/'.APP_BASE_PATH.'/todo/category';
header("Location: $path");
}
    }

    
    public function delete($params){
      $this->check->requirePermission(); 
      $todoCategoryModel = new CategoryModel();
      $todoCategoryModel->deleteCategory($params['id']);
      $path='/'.APP_BASE_PATH.'/todo/category';
       header("Location: $path");
    }

}