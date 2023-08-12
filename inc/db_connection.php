<?php
//PDO (type:host=ip_of_server_hold_DB;dbname=name_of_DB , "username" , "password")
try {
    $conn = new PDO("mysql:host=localhost;dbname=ToDoApp", "root", "");
    // echo "connected";
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
