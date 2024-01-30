<?php 
 
$title ='Update page';
ob_start();
tt($page);
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Update Page</h1>

<form action="/<?=APP_BASE_PATH?>/pages/update" method="post">
<input type="hidden" name="id" value="<?=$page['id'];?>">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?=$page['title'];?>" required>
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <!-- <textarea class="form-control" name="slug" id="slug" cols="30" rows="10"><?=$page['slug'];?></textarea> -->
        <input type="text" class="form-control" id="slug" name="slug" value="<?=$page['slug'];?>" required>
    </div>

    <div id="roles-container" class="mb-3">
        <label for="roles" class="form-label">Roles</label>
        <?php $page_role=explode(',', $page['role']);?>
        <?php foreach($roles as $role){?>
            <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="<?=$role['id']?>"
             <?php echo in_array($role['id'],$page_role) ? 'checked' : ''?>> 
            <label for="roles" class="form-check-label"><?php echo $role['role_name'];?></label>   
            </div>
        <?php }?>
    </div>
    <button type="submit" class="btn btn-primary">Update Page</button>
</form>
</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>