<?php 

class Database{

private static $instance= null;
private $conn;
private $host = 'localhost' ;
private $user = 'root' ;
private $pass ='';
private $name ='crm_for_telegram';

private function __construct(){
    $this->conn= new mysqli($this->host,$this->user,$this->pass,$this->name);
    if($this->conn->connect_error){
        die("Connect failed!!!" . $this->conn->connect_error);
    }
}

public static function tte($value){
  echo '<pre>';
  print_r($_POST);
  echo '</pre>';
  exit();
}
public static function tt($value){
  echo '<pre>';
  print_r($_POST);
  echo '</pre>';
}
// возвращает сам обьект класса Database
public static function getInstance(){

    if(!self::$instance){
        self::$instance =new Database();
    }
    return self::$instance;
}
// метод который возвращает обьект подключения к бд
public function getConnection(){

    return $this->conn;
}


}


