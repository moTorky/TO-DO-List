<?php
include './inc/db_connection.php';
include './inc/input_santize.php';

if (isset($_POST['submit']) && !empty($_POST['username']) && !empty($_POST['pass'])){

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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge"> 

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&amp;display=swap">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css">
<title>Todo App Login</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }
  .container {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 300px;
    text-align: center;
  }
  h1 {
    margin-bottom: 20px;
  }
  input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }
  input[type="submit"] {
    background-color: #007BFF;
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
  }
  input[type="submit"]:hover {
    background-color: #0056b3;
  }
</style>
</head>
<body>
<div class="container">
  <h1>Welcome to:</h1>
  <br><div class="p-1 h1 text-primary text-center mx-auto display-inline-block">
    <i class="fa fa-check bg-primary text-white rounded p-2"></i>
    <u>My Todo-s</u>
</div>
  <!-- <h1>Todo App</h1> -->
  <form method="POST" action="register.php"> <!-- Replace 'dashboard.html' with your actual dashboard page -->
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="pass" placeholder="Password">
    <input type="submit" name="submit" value="register">
    <?php
    // Display errors to the user
    if (isset($error['SQLi'])) {
        echo "<div class='alert alert-danger'>" . $error['SQLi'] . "</div>";
    }
    ?>
  </form>
</div>
</body>
</html>