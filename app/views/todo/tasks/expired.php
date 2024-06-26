<?php 
$title ='Todo List';
ob_start();

//  tte($tasks);
?>

<h1 class="mb-4">Todo List</h1>
<div class="d-flex justify-content-around row filter-priority">
<a data-priority="low" class="btn mb-3 col-2 sort-btn" href="" style="background-color: #51A5F4">Low</a>
<a data-priority="medium" class="btn mb-3 col-2 sort-btn" href="" style="background-color:#3C7A85">Medium</a>
<a data-priority="high" class="btn mb-3 col-2 sort-btn" href="" style="background-color: #274F75">High</a>
<a data-priority="urgent" class="btn mb-3 col-2 sort-btn" href="" style="background-color: #122436">Urgent</a>
</div>
    <div class="accordion" id="tasks-accordion">
        <?php 
        // tte($tasks);
        foreach ($expiredTasks as $oneTask): 
            //  tte($task);
            ?>
            
            <?php 
                $priorityColor='';
                switch($oneTask['priority']){
                  case 'low':
                    $priorityColor='#51A5F4';
                    break;
                  case 'medium':
                    $priorityColor='#3C7A85';
                    break;
                  case 'high':
                    $priorityColor='#274F75';
                    break;
                  case 'urgent':
                    $priorityColor='#122436';
                    break;

                }
                ?>

                
            <div class="accordion-item mb-2">
                <div class="accordion-header d-flex justify-content-between align-items-center row" id="task-<?php echo $oneTask['id']; ?>">
                    <h2 class="accordion-header col-12 col-md-6">
                        <button style="background-color: <?=$priorityColor?>;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#task-collapse-<?php echo $oneTask['id']; ?>"
                         aria-expanded="false" aria-controls="task-collapse-<?php echo $oneTask['id']; ?>" data-priority="<?php echo $oneTask['priority']; ?>">
                            <span class="col-12 col-md-5"><i class="fa-solid fa-square-up-right"></i> <strong><?php echo $oneTask['title']; ?> </strong></span>
                            <span class="col-5 col-md-3"><i class="fa-solid fa-person-circle-question"></i> <?php echo $oneTask['status']; ?> </span>
                            <span class="col-5 col-md-3"><i class="fa-solid fa-hourglass-start"></i><span class="due-date"><?php echo $oneTask['finish_date']; ?></span></span>
                        </button>
                    </h2>
                </div>
                <div id="task-collapse-<?php echo $oneTask['id']; ?>" class="accordion-collapse collapse row" 
                aria-labelledby="task-<?php echo $oneTask['id']; ?>" data-bs-parent="#tasks-accordion">

            <div class="accordion-body">
                        <p class="row">
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-layer-group"></i> Category:</strong> <?php echo htmlspecialchars($oneTask['category']['title'] ?? 'N/A'); ?></span>
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-battery-three-quarters"></i> Status:</strong> <?php echo htmlspecialchars($oneTask['status']); ?></span>
                        </p>
                        <p class="row">
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-person-circle-question"></i> Priority:</strong> <?php echo htmlspecialchars($oneTask['priority']); ?></span>
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-hourglass-start"></i> Due Date:</strong> <?php echo htmlspecialchars($oneTask['finish_date']); ?></span>
                        </p>
                        <p><strong><i class="fa-solid fa-file-prescription"></i> Tags:</strong> 
                        
                            <?php
                            //   tte($task['tags']);
                            foreach ($oneTask['tags'] as $tag):
                               // tte($tag);
                            ?>
                           
                                <a href="/todo/tasks/by-tag/<?= $tag['id'] ?>" class="tag"><?=$tag['name'] ? htmlspecialchars($tag['name']) : ''?></a>
                            <?php endforeach; ?>
                        </p>
                        <p>
                            <strong><i class="fa-solid fa-file-prescription"></i> Description:</strong> <em><?php echo htmlspecialchars($oneTask['description'] ?? ''); ?></em>
                        </p>
                        <hr>
                        <div class="d-flex justify-content-start action-task">

                        <form action="/todo/tasks/update-status/<?php echo $oneTask['id'];?>" method="post" class="me-2">
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn <?=$oneTask['status']=='cancelled' ? 'btn-dark' : 'btn-secondary'; ?>">Cancelled</button>
                        </form>
                        <form action="/todo/tasks/update-status/<?php echo $oneTask['id'];?>" method="post" class="me-2">
                    <input type="hidden" name="status" value="new">
                    <button type="submit" class="btn <?=$oneTask['status']=='new' ? 'btn-dark' : 'btn-secondary'; ?>">New</button>
                        </form>
                        <form action="/todo/tasks/update-status/<?php echo $oneTask['id'];?>" method="post" class="me-2">
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="btn <?=$oneTask['status']=='in_progress' ? 'btn-dark' : 'btn-secondary'; ?>">In progress</button>
                        </form>
                        <form action="/todo/tasks/update-status/<?php echo $oneTask['id'];?>" method="post" class="me-2">
                    <input type="hidden" name="status" value="on_hold">
                    <button type="submit" class="btn <?=$oneTask['status']=='on_hold' ? 'btn-dark' : 'btn-secondary'; ?>">On hold</button>
                        </form>
                        <form action="/todo/tasks/update-status/<?php echo $oneTask['id'];?>" method="post" class="me-2">
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn <?=$oneTask['status']=='completed' ? 'btn-dark' : 'btn-secondary'; ?>">Completed</button>
                        </form>

                            <a href="/todo/tasks/edit/<?php echo $oneTask['id']; ?>" class="btn btn-primary me-2">Edit</a>
                            <a href="/todo/tasks/delete/<?php echo $oneTask['id']; ?>" class="btn btn-danger me-2">Delete</a>
                        </div>
                    </div>


                </div>
            </div>   
                            <?php endforeach; ?>
    </div>

<?php $content=ob_get_clean();
include 'app/views/layout.php'; ?>

