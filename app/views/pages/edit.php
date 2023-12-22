<?php 
 
$title ='Update page';
ob_start();
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Update Page</h1>

<form action="index.php?page=pages&action=update" method="post">
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
    <button type="submit" class="btn btn-primary">Update Page</button>
</form>
</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>