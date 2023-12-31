<?php
include './inc/db_connection.php';
include './inc/header.php';
include './inc/footer.php';
include './inc/input_santize.php';

function input_snatize($string){

    return $string;
}
$user_id=$_SESSION['user_id'];
$query = $conn->prepare('SELECT * FROM `tasks` WHERE `user_id`='.$user_id.' AND parent_task_id IS NULL');
$query->execute();
$tasks = $query->fetchAll(PDO::FETCH_ASSOC); 
$errors=[];

if (!empty($_POST['task_name']) && $_POST['action'] == 'insert') {
    $task_name = input_snatize($_POST['task_name']);
    $due_date=date("Y-m-d", strtotime("+7 Days"));
    if (!empty($_POST['due_date'])){
        $due_date = input_snatize($_POST['due_date']);
    }

    try{
        //INSERT INTO `tasks`(`user_id`, `task_id`, `task_name`, `parent_task_id`, `due_date`, `status`) VALUES 
        //('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]')
        $query= $conn->prepare('INSERT INTO `tasks` (`user_id`, `task_name`, `due_date`, `status`) VALUES (?,?,?,?)');
        $result = $query->execute([$user_id, $task_name, $due_date, 0]);
        if ($result){
            $errors['insert'] = 'task insert sucsfully';
        }else{
            $errors['insert'] = 'can\'t insert task';
        }
        header("Location: index.php");
    }catch(Exception $e){
        // echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }
}

if (!empty($_POST['task_id']) && $_POST['action'] == 'delete') {
    try{
        $task_id= input_snatize($_POST['task_id']);
        $query1= $conn->prepare('DELETE FROM `tasks` WHERE parent_task_id='.$task_id);
        $result1=$query1->execute();
        $query2= $conn->prepare('DELETE FROM `tasks` WHERE task_id='.$task_id);
        $result2=$query2->execute();
        if ($result2){
            $errors['delete'] = 'suptasks deleted sucsfully';
        }else{
            $errors['delete'] = 'can\'t delete suptask';
        } 
        if ($result2){
            $errors['delete'] = 'task deleted sucsfully';
        }else{
            $errors['delete'] = 'can\'t delete task';
        } 
        header("Location: index.php");
    }catch(Exception $e){
        // echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }
}

if (!empty($_POST['task_id']) && $_POST['action'] == 'update') {
    $task_id= input_snatize($_POST['task_id']);
    header("Location: viewTask.php?id=".$task_id);
    exit();
}
function insert_new_task($name,$due_date,$discription,$id){

}
?>
    <div class="container m-5 p-2 rounded mx-auto bg-light shadow">
        <!-- App title section -->
        <div class="row m-1 p-4">
            <div class="col">
                <div class="p-1 h1 text-primary text-center mx-auto display-inline-block">
                    <i class="fa fa-check bg-primary text-white rounded p-2"></i>
                    <u>My Todo-s</u>
                </div>
            </div>
        </div>
        <!-- Create todo section -->
        <form action="index.php" method="post">
        <div class="row m-1 p-3">
            <div class="col col-11 mx-auto">
                <div class="row bg-white rounded shadow-sm p-2 add-todo-wrapper align-items-center justify-content-center">
                    <div class="col">
                        <input class="form-control form-control-lg border-0 add-todo-input bg-transparent rounded" type="text" placeholder="Add new .." name='task_name'>
                        <input type="text" name='action' value="insert" hidden>
                    </div>
                    <div class="col-auto m-0 px-2 d-flex align-items-center">
                        <label class="text-secondary my-2 p-0 px-1 view-opt-label due-date-label d-none">Due date not set</label>
                        <input class="fa fa-calendar-times-o my-2 px-1 text-danger btn clear-due-date-button" type="date" name="due-date" data-toggle="tooltip" data-placement="bottom" title="Set a Due date">
                        <i class="fa fa-calendar my-2 px-1 text-primary btn due-date-button" data-toggle="tooltip" data-placement="bottom" title="Set a Due date"></i>
                        <i class="fa fa-calendar-times-o my-2 px-1 text-danger btn clear-due-date-button d-none" data-toggle="tooltip" data-placement="bottom" title="Clear Due date"></i></input>
                    </div>
                    <div class="col-auto px-0 mx-0 mr-2">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>
        </form>

        <div class="p-2 mx-4 border-black-25 border-bottom"></div>
        <!-- View options section -->
        <div class="row m-1 p-3 px-5 justify-content-end">
            <div class="col-auto d-flex align-items-center">
                <label class="text-secondary my-2 pr-2 view-opt-label">Filter</label>
                <select class="custom-select custom-select-sm btn my-2">
                    <option value="all" selected>All</option>
                    <option value="completed">Completed</option>
                    <option value="active">Active</option>
                    <option value="has-due-date">Has due date</option>
                </select>
            </div>
            <div class="col-auto d-flex align-items-center px-1 pr-3">
                <label class="text-secondary my-2 pr-2 view-opt-label">Sort</label>
                <select class="custom-select custom-select-sm btn my-2">
                    <option value="added-date-asc" selected>Added date</option>
                    <option value="due-date-desc">Due date</option>
                </select>
                <i class="fa fa fa-sort-amount-asc text-info btn mx-0 px-0 pl-1" data-toggle="tooltip" data-placement="bottom" title="Ascending"></i>
                <i class="fa fa fa-sort-amount-desc text-info btn mx-0 px-0 pl-1 d-none" data-toggle="tooltip" data-placement="bottom" title="Descending"></i>
            </div>
        </div>
        <!-- Todo list section -->
        <div class="row mx-1 px-5 pb-3 w-80">
            <div class="col mx-auto">
                <!-- Todo Item 1 -->
                <?php foreach ($tasks as $task){ ?>
                <div class="row px-3 align-items-center todo-item rounded">
                    <div class="col-auto m-1 p-0 d-flex align-items-center">
                        <h2 class="m-0 p-0">
                            <i class="fa fa-square-o text-primary btn m-0 p-0 d-none" data-toggle="tooltip" data-placement="bottom" title="Mark as complete"></i>
                            <i class="fa <?php if($task['status'] != 2)echo 'fa-square-o'; else echo 'fa-check-square-o'?> text-primary btn m-0 p-0" data-toggle="tooltip" data-placement="bottom" title="Mark as todo"></i>
                        </h2>
                    </div>
                    <div class="col px-1 m-1 d-flex align-items-center">
                        <input type="text" class="form-control form-control-lg border-0 edit-todo-input bg-transparent rounded px-3" readonly value="<?php echo $task['task_name'];?>" title="<?php echo $task['task_name'];?>" />
                        <input type="text" class="form-control form-control-lg border-0 edit-todo-input rounded px-3 d-none" value="<?php echo $task['task_name'];?>" />
                    </div>
                    <div class="col-auto m-1 p-0 px-3 d-none">
                    </div>
                    <div class="col-auto m-1 p-0 todo-actions">
                        <div class="row d-flex align-items-center justify-content-end">
                            <form action="index.php" method="post">
                                <h5 class="m-0 p-0 px-2">
                                    <input type="text" name="action" id="" value="update" hidden>
                                    <input type="text" name="task_id" id="" value=<?php echo $task['task_id'];?> hidden>
                                    
                                    <button type="submit">
                                    <i class="fa fa-pencil text-info btn m-0 p-0" data-toggle="tooltip" data-placement="bottom" title="Edit todo"></i>
                                    </button>
                                </h5>
                            </form>
                            <form action="index.php" method="post">
                                <h5 class="m-0 p-0 px-2">
                                    <input type="text" name="action" id="" value="delete" hidden>
                                    <input type="text" name="task_id" id="" value=<?php echo $task['task_id'];?> hidden>
                                    <button type="submit">
                                    <i class="fa fa-trash-o text-danger btn m-0 p-0" data-toggle="tooltip" data-placement="bottom" title="Delete todo"></i>
                                    </button>
                                </h5>
                            </form>
                        </div>
                        <div class="row todo-created-info">
                            <div class="col-auto d-flex align-items-center pr-2">
                                <i class="fa fa-info-circle my-2 px-2 text-black-50 btn" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Created date"></i>
                                <label class="date-label my-2 text-black-50"><?php echo $task['due_date'];?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php include './inc/footer.php'?>