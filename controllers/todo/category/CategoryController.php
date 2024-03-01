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
  include 'app/views/todo/category/index.php';
    }

    
    public function create(){
       $this->check->requirePermission(); 
  include 'app/views/todo/category/create.php';
    }


    public function store(){
       $this->check->requirePermission(); 
if(isset($_POST['title'])&&isset($_POST['description'])){
$title= trim(htmlspecialchars($_POST['title']));
$description= trim(htmlspecialchars($_POST['description']));
$user_id=isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if(empty($title)||empty($description)){
  echo "Title and Description are required!!!";
  return;
}else{

$todoCategoryModel = new CategoryModel();
$todoCategoryModel->createCategory($title,$description,$user_id);
}
$path='/todo/category';
header("Location: $path");
}
    }

    public function edit($params){
    
       $this->check->requirePermission(); 

      $id=$params['id'];
     
     $todoCategoryModel = new CategoryModel();
      $category=$todoCategoryModel->getCategoryById($id);
      // tte( $category);
      $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
      
      // tte( $task);
        if(!$category || $category['user'] !=$user_id){
 
         http_response_code(404);
         include 'app/views/errors/404.php';
           return;
        }

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
$title= trim(htmlspecialchars($_POST['title']));
$description= trim(htmlspecialchars($_POST['description']));
$usability=isset($_POST['usability']) ? isset($_POST['usability']) : 0;
if(empty($title)||empty($description)){
  echo "Title and description are required!!!";
  return;
}else{

  $todoCategoryModel = new CategoryModel();
  $todoCategoryModel->updateCategory($id,$title,$description,$usability);
}
$path='/todo/category';
header("Location: $path");
}
    }

    
    public function delete($params){
      $this->check->requirePermission(); 
      $todoCategoryModel = new CategoryModel();
      $todoCategoryModel->deleteCategory($params['id']);
      $path='/todo/category';
       header("Location: $path");
    }

}