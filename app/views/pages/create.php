<?php 
$title ='Create Page';
ob_start();
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Create Page</h1>

<form action="index.php?page=pages&action=store" method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" required>
    </div>
   
    <button type="submit" class="btn btn-primary">Create Page</button>
</form>
</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>