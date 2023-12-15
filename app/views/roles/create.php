<?php 
$title ='Create role';
ob_start();
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Create Role</h1>

<form action="index.php?page=roles&action=store" method="post">
    <div class="mb-3">
        <label for="role_name" class="form-label">Role name</label>
        <input type="text" class="form-control" id="role_name" name="role_name" required>
    </div>
    <div class="mb-3">
        <label for="role_description" class="form-label">Role Description</label>
        <textarea class="form-control" id="role_description" name="role_description" required></textarea>
    </div>
   
    <button type="submit" class="btn btn-primary">Create Role</button>
</form>
</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>