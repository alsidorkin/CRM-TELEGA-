<?php 
$title ='Edit user';
ob_start();
// Database::tte($roles);
?>
<h1>Edit user</h1>

<form action="index.php?page=users&action=update&id=<?=$user['id']?>" method="post">
    <div class="mb-3">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" value="<?=$user['username']?>" name="username" required>
    </div>
    <div class="mb-3">
        <label for="email">Email address</label>
        <input type="text" class="form-control" id="email" value="<?=$user['email']?>" name="email" required>
    </div>
    <div class="mb-3">
        <label for="role">Role</label>
        <select name="role" class="form-control" id="role">
            <?php  foreach($roles as $role){ ?>
               
<option value="<?php echo $role['id']?>" <?php echo $user['role']==$role['id'] ? 'selected' : '';?>>
<?=$role['role_name'];?></option>
            <?php } ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>


<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>