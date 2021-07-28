<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//     header("location: welcome.php");
//     exit;
// }
 
// Include config file
require_once "pdo.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        
    } else{
        echo("ERROR4");
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_name = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["user_id"];
                        $username = $row["user_name"];
                        $hashed_password = $row["user_password"];

                        if(($password == $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            // $_SESSION["loggedin"] = true;
                             $_SESSION["user_id"] = $id;                    
                    
                            
                            // Redirect user to welcome page
                            header("location: home/homepage.php");
                        } else{
                            echo("ERROR1");
                            // Password is not valid, display a generic error message
                            // $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    echo("ERROR2");
                    // Username doesn't exist, display a generic error message
                    // $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Login Form</title>
     <link rel="stylesheet" type="text/css" href="stylesLogin.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 </head>

<body>
    <h2>EasyDeals</h2><br>
    <div class="login">
        <form id="login" method="POST" action="index.php">
            <label><b>User Name</b></label>
            <input type="text" name="username" id="username" placeholder="Username">
            <br><br>
            <label><b>Password</b></label>
            <input type="Password" name="password" id="password" placeholder="Password">
            <br><br>
            <div class="button">
                <button class="button1">Sign in</button>
                <button class="button2">Sign out</button>
            </div>
            <br><br>
            <input type="checkbox" name="check" id="check">
            <span style="color:black">Remember me</span>
        </form>
    </div>
<!--     <div class="icon-bar">
        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
        <a href="#" class="google"><i class="fa fa-google"></i></a>
    </div> -->

</body>

</html>