<?php 
require_once 'app/models/pages/PageModel.php';
class PageController{

    public function index(){
  $pageModel = new PageModel();
  $pages=$pageModel->getAllPages();
  // Database::tte($roles);
  include 'app/views/pages/index.php';
    }

    
    public function create(){

  include 'app/views/pages/create.php';
    }


    public function store(){
     
      // Database::tte($_POST);
if(isset($_POST['title'])&&isset($_POST['slug'])){
  // Database::tte($_POST);
$title= trim($_POST['title']);
$slug= trim($_POST['slug']);

if(empty($title)||empty($slug)){
  echo "Title and slug fields are required!!!";
  return;
}else{

$pageModel = new PageModel();
// Database::tte($role_name.$role_description);
$pageModel->createPage($title,$slug);
}
header("Location: index.php?page=pages");
}
    }

    public function edit($id){
        $pageModel = new PageModel();
      $page=$pageModel->getPageById($id);
      // Database::tte($role);
      if(!$page){
        echo 'Page not found!!!';
        return;
      }
      
      include 'app/views/pages/edit.php';
    }


    public function update(){
     if(isset($_POST['id'])&&isset($_POST['title'])&&isset($_POST['slug'])){
//    Database::tte($_POST);
$id= trim($_POST['id']);
$title= trim($_POST['title']);
$slug= trim($_POST['slug']);

if(empty($title)||empty($slug)){
    echo "Title and slug fields are required!!!";
    return;
  }else{

    $pageModel = new PageModel();
// Database::tte($id.$role_name.$role_description);
$pageModel->updatePage($id,$title,$slug); 
}
header("Location: index.php?page=pages");
}
    }

    
    public function delete(){
        // Database::tte($_GET['id']);
        $pageModel = new PageModel();
        $pageModel->deletePage($_GET['id']);
       header("Location: index.php?page=pages");
    }

}