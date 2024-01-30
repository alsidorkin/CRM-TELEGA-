<?php  
session_start();
?>
<?php
require_once 'config.php';
require_once 'autoload.php';

// error_reporting(E_ALL);
// ini_set('display_errors',1); ///   вывод ошибок





// require_once 'models/Database.php';
// require_once 'models/User.php';
// require_once 'models/AuthUser.php';
// require_once 'models/roles/Role.php';
// require_once 'models/pages/PageModel.php';


// require_once 'controllers/home/HomeController.php';
// require_once 'controllers/users/UsersController.php';
// require_once 'controllers/auth/AuthController.php';
// require_once 'controllers/roles/RoleController.php';
// require_once 'controllers/pages/PageController.php';


// require_once 'app/router.php';
$router = new app\Router();
$router->run();