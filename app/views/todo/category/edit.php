<?php 
 
$title ='Edit category';
ob_start();
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Edit category</h1>

<form action="/<?=APP_BASE_PATH?>/todo/category/update" method="post">
<input type="hidden" name="id" value="<?=$category['id'];?>">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?=$category['title'];?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" id="description" cols="30" rows="10"><?=$category['description'];?></textarea>
    </div>
    <div class="mb-3">
        <label for="usability " class="form-label">Usability</label>
        <input type="checkbox" class="form-check-input" id="usability" name="usability"
         value="1" <? echo $category['usability'] ? 'checked' : '';?>>
    </div>
    <button type="submit" class="btn btn-primary">Update category</button>
</form>
</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>