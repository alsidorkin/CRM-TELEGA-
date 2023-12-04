<?php 

if($_SERVER['REQUEST_URI'] == '/crm_for_telegram/index.php'){

    header('Location: /crm_for_telegram/');
    exit();
}
$title ='User list';
ob_start();
?>

<h1>Home page</h1>
<!-- <a href="index.php?page=users&action=create" class="btn btn-success">Create User</a>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Login</th>
      <th scope="col">Admin</th>
      <th scope="col">Created At</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php //foreach($users as $user){ ?>
    <tr>
      <th scope="row"><?//=$user['id']?></th>
      <td><?//=$user['login']?></td>
      <td><?//=$user['is_admin'] ? 'YES' : 'NO'?></td>
      <td><?//=$user['created_at']?></td>
      <td>
        <a href="index.php?page=users&action=edit&id=<?//=//$user['id']?>" class="btn btn-primary">Edit</a>
        <a href="index.php?page=users&action=delete&id=<?//=$user['id']?>" class="btn btn-danger">Delete</a>
    
    </td>
    </tr>
    <?php // } ?>
    
   
  </tbody>
</table> -->

<?php $content=ob_get_clean();
include 'app/views/layout.php';