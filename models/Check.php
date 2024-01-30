<?php 
namespace models;
use models\pages\PageModel;

class Check{

    private $userRole;

    public function __construct($userRole){
        $this->userRole=$userRole;
    }
    public function getCurrentUrlSlug(){
        $url="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parsedUrl=parse_url($url);
        $path=$parsedUrl['path'];
        $pathWithoutBase=str_replace(APP_BASE_PATH, '', $path);

        $segments=explode('/', ltrim($pathWithoutBase, '/'));
        $firstTwoSegments=array_slice($segments, 0, 2);
        $slug=implode('/',$firstTwoSegments);
        return $slug;// обрезаем / вначале строки
    }

    public function checkPremission($slug){
        $pageModel=new PageModel();
        $page=$pageModel->findBySlug($slug);
        if(!$page){
          return false;
        }
        // получение роли для страницы
        $allowedRoles= explode(',' , $page['role']);
        //проверить имеет ли текущий пользователь доступ к странице
        if (isset($_SESSION['user_role'])&& in_array($_SESSION['user_role'], $allowedRoles)){
          return true;
        }else{
          return false;
        }
      }
  
public function requirePermission(){
    $slug=$this->getCurrentUrlSlug();
    if(!$this->checkPremission($slug)){
        $path ='/'.APP_BASE_PATH;
       header("Location: $path");
       exit();
    }
}

public function isCurrentUserRole($role){
return $this->userRole == $role;
}

}