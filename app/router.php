<?php  

class Router{

    public function run(){
  $page=isset($_GET['page']) ? $_GET['page'] :'home';
  switch($page){
      case '':

      case 'home' :
      $controller = new HomeController();
      $controller->index();
      break;
      


      
      case 'users':
        $controller = new UsersController();
              if(isset($_GET['action'])){

                switch($_GET['action']){
     
              case 'create':
                $controller->create();
                break;

              case 'store':
                // Database::tte($_POST);
                $controller->store();
                break;

              case 'edit':
                // Database::tte($_POST);
                $controller->edit();
                break;
              case 'update':
                // Database::tte($_POST);
                $controller->update();
                break;
              case 'delete':
                // Database::tte($_POST);
                $controller->delete();
                break;
            }

        }else{
          $controller->index();
        }

                break;

                case 'roles':
                  $controller = new RoleController();
                  
                        if(isset($_GET['action'])){
          
                          switch($_GET['action']){
               
                        case 'create':
                          $controller->create();
                          break;
          
                        case 'store':
                          // Database::tte($_POST);
                          $controller->store();
                          break;
          
                        case 'edit':
                          // Database::tte($_POST);
                          $controller->edit($_GET['id']);
                          break;
                        case 'update':
                          // Database::tte($_POST);
                          $controller->update();
                          break;
                        case 'delete':
                          // Database::tte($_POST);
                          $controller->delete();
                          break;
                      }
          
                  }else{
                    $controller->index();
                  }
          
                          break;


                          case 'pages':
                            $controller = new PageController();
                            
                                  if(isset($_GET['action'])){
                    
                                    switch($_GET['action']){
                         
                                  case 'create':
                                    $controller->create();
                                    break;
                    
                                  case 'store':
                                    // Database::tte($_POST);
                                    $controller->store();
                                    break;
                    
                                  case 'edit':
                                    // Database::tte($_POST);
                                    $controller->edit($_GET['id']);
                                    break;
                                  case 'update':
                                    // Database::tte($_POST);
                                    $controller->update();
                                    break;
                                  case 'delete':
                                    // Database::tte($_POST);
                                    $controller->delete();
                                    break;
                                }
                    
                            }else{
                              $controller->index();
                            }
                    
                                    break;


        case 'register': 
          $controller = new AuthController();
          $controller->register();
          break;

        case 'login': 
          $controller = new AuthController();
          $controller->login();
          break;

        case 'authenticate': 
          $controller = new AuthController();
          $controller->authenticate();
          break;

        case 'logout': 
          $controller = new AuthController();
          $controller->logout();
          break;

          case 'auth':
            $controller = new AuthController();
                  if(isset($_GET['action'])){
    
                    switch($_GET['action']){
    
                  case 'store':
                    // Database::tte($_POST);
                    $controller->store();
                    break;

                  case 'authenticate':
                    // Database::tte($_POST);
                    $controller->authenticate();
                    break;
    
                }
    
            }else{
              $controller->login();
            }
    
                    break;


        default:
        http_response_code(404);
        echo 'Page not found!';
        break;
  }

    }
}
