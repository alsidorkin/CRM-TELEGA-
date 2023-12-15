<?php 
$title ='User list';
ob_start();
?>

<h1>User list</h1>
<a href="index.php?page=users&action=create" class="btn btn-success">Create User</a>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Username</th>
      <th scope="col">Email</th>
      <th scope="col">Email verification At</th>
      <th scope="col">Is admin</th>
      <th scope="col">Role</th>
      <th scope="col">Is active</th>
      <th scope="col">Last login</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // if (is_array($users) || is_object($users)) {
    foreach($users as $user){ ?>
    <tr>
      <td><?=$user['id']?></td>
      <td><?=$user['username']?></td>
      <td><?=$user['email']?></td>
      <td><?=$user['email_verification']?></td>
      <td><?=$user['is_admin'] ? 'YES' : 'NO'?></td>
      <td><?=$user['role']?></td>
      <td><?=$user['is_active'] ? 'YES' : 'NO'?></td>
      <td><?=$user['last_login']?></td>
      <td>
        <a href="index.php?page=users&action=edit&id=<?=$user['id']?>" class="btn btn-primary">Edit</a>
        <a href="index.php?page=users&action=delete&id=<?=$user['id']?>" class="btn btn-danger">Delete</a>
    
    </td>
    </tr>
    <?php }
  // } ?>
    
   
  </tbody>
</table>

<?php $content=ob_get_clean();
include 'app/views/layout.php';