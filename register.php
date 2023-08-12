<?php
include './inc/db_connection.php';
include './inc/header.php';
include './inc/footer.php';
include './inc/input_santize.php';

if (!empty($_POST('username')) && !empty($_POST('pass'))){

    $user = input_santize($_POST['username']);
    $pass = input_santize($_POST['pass']);
    $errors=[];

    if (str_contains($user,'SQLi detection') || str_contains($pass, 'SQLi detection')){
        $errors['SQLi']='SQLi detection';
    }
    $pass = sha1($pass);

    if(empty($errors)){
        try{
            $query = $conn->prepare('INSERT INTO `users` (`username`,`password`) VALUES (?,?)');
            $resulte = $query->execute([$user,$pass]);
            if($resulte){
                header('location: login.php');
            }
        }catch(Exception $e){
            echo $e.'a7a';
        }
    }
}
?>