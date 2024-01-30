<?php 

if($_SERVER['REQUEST_URI'] == '/CRM-FOR-TELEGRAM/index.php'){

    header('Location: /CRM-FOR-TELEGRAM/');
    exit();
}
$title ='User list';
ob_start();
?>

<h1>Home page</h1>

<?php $content=ob_get_clean();
include 'app/views/layout.php';