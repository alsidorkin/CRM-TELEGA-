<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 function tte($value){
    echo '<pre>';
    print_r($value);
    echo '</pre>';
    exit();
  }
  function tt($value){
    echo '<pre>';
    print_r($value);
    echo '</pre>';
  }

// const APP_BASE_PATH='CRM-FOR-TELEGRAM';

define('DB_HOST' , 'localhost');
define('DB_USER' , 'root');
define('DB_PASS' , '');
define('DB_NAME' , 'crm_for_telega');

define('ENABLE_PERMISSION_CHECK', true); // Установка значение в false, чтобы отключить проверки разрешений в контроллерах

// const START_ROLE= 1;


// return [
//  'db_host' => 'localhost' ,
//  'db_user' => 'root' ,
//  'db_pass' => '' ,
//  'db_name' => 'crm_for_telegram',
//  'start_role'=> '1'
// ];





