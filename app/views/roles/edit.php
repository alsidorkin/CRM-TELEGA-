<?php 
 
$title ='Create role';
ob_start();
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Edit Role</h1>

<form action="index.php?page=roles&action=update" method="post">
<input type="hidden" name="id" value="<?=$role['id'];?>">
    <div class="mb-3">
        <label for="role_name" class="form-label">Role name</label>
        <input type="text" class="form-control" id="role_name" name="role_name" value="<?=$role['role_name'];?>" required>
    </div>
    <div class="mb-3">
        <label for="role_description" class="form-label">Role Description</label>
        <textarea class="form-control" name="role_description" id="role_description" cols="30" rows="10"><?=$role['role_description'];?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Role</button>
</form>
</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>