<?php 

class User{

    private $db;

    public function __construct(){
        $this->db=Database::getInstance()->getConnection();
    }

    public function readAll(){
        $result=$this->db->query("SELECT * FROM users");

        $users=[];

        while($row=$result->fetch_assoc()){
            $users[]=$row;
        }
        return $users;
    }
    public function create($id,$data){
       $login=$data['login'];
       $password=password_hash($data['password'] , PASSWORD_DEFAULT);
    //    $admin=isset($data['is_admin']) ?  1 : 0;
       $admin=!empty($data['is_admin'])&&$data['is_admin']!==0 ?  1 : 0;
       $created_at=date('Y-m-d H:i:s');
       $stmt=$this->db->prepare( " INSERT INTO users (login,password, is_admin ,  created_at)  VALUE (?,?,?,?)");
       $stmt->bind_param("ssis" , $login , $password, $admin, $created_at);
       
if($stmt->execute()){
    return true;
}
else{
    return false;
}
    
    }


    public function update($id, $data){
  $login=$data['login'];
  $admin=!empty($data['is_admin'])&&$data['is_admin']!==0 ? 1 : 0;
  $stmt=$this->db->prepare("UPDATE users SET login=?, is_admin=? WHERE id=?");
  $stmt->bind_param("ssi" , $login, $admin,$id);
       
    if($stmt->execute()){
        return true;
    }
    else{
        return false;
    }
        }



    public function read($id){
        $stmt=$this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt-> bind_param("i",$id);
        $stmt-> execute();

        $result=$stmt->get_result();
        $user=$result->fetch_assoc();
        return $user;

     
    }
    public function delete($id){

        $stmt=$this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt-> bind_param("i",$id);

        if($stmt->execute()){
            return true;
        
        
        }
        else{
            return false;
        }
    }
    
}