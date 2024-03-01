<?php 
$title ='Create Page';
ob_start();
// tt($roles);
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Create Page</h1>

<form action="/pages/store" method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" required>
    </div>
    <div id="roles-container" class="mb-3">
        <label for="roles" class="form-label">Roles</label>
        <?php foreach($roles as $role){?>
            <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="<?=$role['id']?>"> 
            <label for="roles" class="form-check-label"><?php echo $role['role_name'];?></label>   
            </div>
        <?php }?>
    </div>
   
    <button type="submit" class="btn btn-primary">Create Page</button>
</form>
</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>