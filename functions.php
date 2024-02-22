<?php 

function is_active($path){
    $currentPath=$_SERVER['REQUEST_URI'];
    return $path === $currentPath ? 'active' : '';

}