<?php  

// укажем пространство имен для класса 

namespace app;

class Router{
// определяем маршруты с помощью регулярных выражений
  private $routes =[

    '/^\/?$/'=> ['controller' => 'home\\HomeController' , 'action' => 'index'] ,
    '/^\/users(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'users\\UsersController'],
    '/^\/auth(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'auth\\AuthController'],
    '/^\/roles(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'roles\\RoleController'],
    '/^\/pages(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'pages\\PageController'],
    '/^\/(register|login|authenticate|logout)(\/(?P<action>[a-z]+))?$/' => ['controller' => 'auth\\AuthController'],
    '/^\/todo\/category(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'todo\category\\CategoryController'],
    '/^\/todo\/tasks(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'todo\tasks\\TaskController'],
    '/^\/todo\/tasks\/by-tag(\/(?P<id>\d+))?$/' => ['controller' => 'todo\tasks\\TaskController', 'action' => 'tasksByTag'],
    '/^\/todo\/tasks\/update-status(\/(?P<id>\d+))?$/' => ['controller' => 'todo\tasks\\TaskController', 'action' => 'updateStatus'],
    '/^\/todo\/tasks\/task(\/(?P<id>\d+))?$/' => ['controller' => 'todo\tasks\\TaskController', 'action' => 'task'],
  ];
       

  public function run(){
   $uri=$_SERVER['REQUEST_URI'];
     //tt($uri);
      $controller=null;
      $action=null;
      $params=null;
// echo "URI: $uri<br>";
// пробегаемся по маршрутам ($routes) пока не найдем нужный
foreach($this->routes as $pattern=>$route){
  // tt($route);
// ищем маршрут который соответствует URI при помощи регулярного выражения
  if(preg_match($pattern,$uri,$matches)){ //  /^\/crm_for_telegram\/?$/
    // получаем имя контроллера с маршрута ($route )
    $controller="controllers\\" . $route['controller'];
    //tt($controller);
    // if()
    // получаем действие из маршрута если оно есть или из URI 
    $action=$route['action'] ?? $matches['action'] ?? 'index';
    //  tt($action);
    // получаем параметры из  совпавших с регулярных выражений подстрок
    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    // прерываем цикл если нашли подходящий маршрут
    break;
  }
}
if(!$controller){
http_response_code(404);
echo 'Page not found!';
return;
}
  $controllerInstance = new $controller();
// tt($action);
  if(!method_exists($controllerInstance, $action)){
    http_response_code(404);
      echo 'Action not found!';
      return;
  }

  call_user_func_array([$controllerInstance, $action],[$params]);
  }

}





//     public function run(){
//   $page=isset($_GET['page']) ? $_GET['page'] :'home';
//   switch($page){
//       case '':

//       case 'home' :
//       $controller = new HomeController();
//       $controller->index();
//       break;
      


      
//       case 'users':
//         $controller = new UsersController();
//               if(isset($_GET['action'])){

//                 switch($_GET['action']){
     
//               case 'create':
//                 $controller->create();
//                 break;

//               case 'store':
//                 // Database::tte($_POST);
//                 $controller->store();
//                 break;

//               case 'edit':
//                 // Database::tte($_POST);
//                 $controller->edit();
//                 break;
//               case 'update':
//                 // Database::tte($_POST);
//                 $controller->update();
//                 break;
//               case 'delete':
//                 // Database::tte($_POST);
//                 $controller->delete();
//                 break;
//             }

//         }else{
//           $controller->index();
//         }

//                 break;

//                 case 'roles':
//                   $controller = new RoleController();
                  
//                         if(isset($_GET['action'])){
          
//                           switch($_GET['action']){
               
//                         case 'create':
//                           $controller->create();
//                           break;
          
//                         case 'store':
//                           // Database::tte($_POST);
//                           $controller->store();
//                           break;
          
//                         case 'edit':
//                           // Database::tte($_POST);
//                           $controller->edit($_GET['id']);
//                           break;
//                         case 'update':
//                           // Database::tte($_POST);
//                           $controller->update();
//                           break;
//                         case 'delete':
//                           // Database::tte($_POST);
//                           $controller->delete();
//                           break;
//                       }
          
//                   }else{
//                     $controller->index();
//                   }
          
//                           break;


//                           case 'pages':
//                             $controller = new PageController();
                            
//                                   if(isset($_GET['action'])){
                    
//                                     switch($_GET['action']){
                         
//                                   case 'create':
//                                     $controller->create();
//                                     break;
                    
//                                   case 'store':
//                                     // Database::tte($_POST);
//                                     $controller->store();
//                                     break;
                    
//                                   case 'edit':
//                                     // Database::tte($_POST);
//                                     $controller->edit($_GET['id']);
//                                     break;
//                                   case 'update':
//                                     // Database::tte($_POST);
//                                     $controller->update();
//                                     break;
//                                   case 'delete':
//                                     // Database::tte($_POST);
//                                     $controller->delete();
//                                     break;
//                                 }
                    
//                             }else{
//                               $controller->index();
//                             }
                    
//                                     break;


//         case 'register': 
//           $controller = new AuthController();
//           $controller->register();
//           break;

//         case 'login': 
//           $controller = new AuthController();
//           $controller->login();
//           break;

//         case 'authenticate': 
//           $controller = new AuthController();
//           $controller->authenticate();
//           break;

//         case 'logout': 
//           $controller = new AuthController();
//           $controller->logout();
//           break;

//           case 'auth':
//             $controller = new AuthController();
//                   if(isset($_GET['action'])){
    
//                     switch($_GET['action']){
    
//                   case 'store':
//                     // Database::tte($_POST);
//                     $controller->store();
//                     break;

//                   case 'authenticate':
//                     // Database::tte($_POST);
//                     $controller->authenticate();
//                     break;
    
//                 }
    
//             }else{
//               $controller->login();
//             }
    
//                     break;


//         default:
//         http_response_code(404);
//         echo 'Page not found!';
//         break;
//   }

//     }
//  }
