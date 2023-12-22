<?php 
//  Database::tte($roles);
$title ='Create pages';
ob_start();
?>
<div class="row justify-content-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-10">
<h1>Pages</h1>
<a href="index.php?page=pages&action=create" class="btn btn-success">Create Pages</a>
<table class="table">
<thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Slug</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php foreach($pages as $page){ ?>
        <tr>
            <td><?=$page['id']?></td>
            <td><?=$page['title']?></td>
            <td><?=$page['slug']?></td>
            <td>
                <a href="index.php?page=pages&action=edit&id=<?= $page['id']?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="POST" action="index.php?page=pages&action=delete&id=<?=$page['id']?>" class="d-inline-block">
        <button type="submit" class=" btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
            </td>
        </tr>
        <?php } ?>
</tbody>
</table>

</div>
</div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>