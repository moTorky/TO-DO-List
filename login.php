<?php
session_start();
include_once('./inc/db_connection.php');
// include_once('./inc/header.php');
include_once('./inc/footer.php');

function input_snatize($string){

    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['username']) && isset($_POST['pass'])){
    $error=[];
    $username = input_snatize($_POST['username']);
    $pass = input_snatize($_POST['pass']);
    

    $pass = sha1($pass);
    
    try{
        if(empty($error)){
            $query = $conn->prepare("SELECT id,password FROM `users` WHERE `username`=?");
            $status=$query->execute([$username]);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);   
                var_dump($result);
            if ($status && $result && $pass === $result[0]['password']){
              
                $_SESSION['user_id']=$result[0]['id'];
                // echo "login succsdully";  
                header('location: index.php');
                exit();
            }
            else{
              $error['login'] = "Invalid Username or Password";
            }
        }

    }catch(Exception $e){
      $error['database'] = "An error occurred while processing your request.";
        // echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
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
  <form method="POST" action=""> <!-- Replace 'dashboard.html' with your actual dashboard page -->
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="pass" placeholder="Password">
    <input type="submit" name="submit" value="Login">
    <?php
    // Display errors to the user
    if (isset($error['sql'])) {
        echo "<div class='alert alert-danger'>" . $error['sql'] . "</div>";
    }
    if (isset($error['login'])) {
        echo "<div class='alert alert-danger'>" . $error['login'] . "</div>";
    }
    if (isset($error['database'])) {
        echo "<div class='alert alert-danger'>" . $error['database'] . "</div>";
    }
    ?>
  </form>
</div>
</body>
</html>
