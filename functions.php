<?php 

function is_active($path){
    $currentPath=$_SERVER['REQUEST_URI'];
    return $path === $currentPath ? 'active' : '';

}


// генерация одноразового ОТР пароля для привязки телеграм аккаунта 

function generationOTP(){
    $otp=rand(1000000,9999999);
    return $otp;
}



