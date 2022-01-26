<?php


session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION['loggedin'] === true) {
    header("location: home.php");
}

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=emeniu', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$username = '';
$password = '';
$username_err = '';
$password_err = '';
$login_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST['username']))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST['username']);
    }
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter password';
    } else {
        $password = $_POST['password'];
    }


   // Validate credentials
   if(empty($username_err) && empty($password_err)){
    // Prepare a select statement
    $sql = "SELECT id, name, password FROM users WHERE name = :username";
    
    if($stmt = $pdo->prepare($sql)){
         // Set parameters
       
        
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        $param_username = trim($_POST["username"]);
       
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Check if username exists, if yes then verify password
            if($stmt->rowCount() == 1){
                if($row = $stmt->fetch()){
                    $id = $row["id"];
                    $username = $row["name"];
                    $hashed_password = $row["password"];
                    if(password_verify($password,$row["password"])){
                        // Password is correct, so start a new session
                        session_start();
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;                            
                        
                        // Redirect user to welcome page
                        header("location: home.php");
                    } else{
                        // Password is not valid, display a generic error message
                        $login_err = "Invalid  password.";
                    }
                }
            } else{
                // Username doesn't exist, display a generic error message
                $login_err = "Invalid username.";
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



<!doctype html>
<html lang="en">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<?php require('navbar.php') ?>

<body>

<?php  
if(!empty($login_err)){
    echo '<div class="alert alert-danger" >' .$login_err
    .'</div>';
}
?>

    <div id="login">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form"  method="post">
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control
                               <?php echo !empty($username_err) ? 'is-invalid' : '' ; ?> "  value="<?php echo $username; ?>"> 
                               <span class="invalid-feedback"><?php echo $username_err ; ?></span>

                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control 
                                 <?php echo !empty($password_err) ? 'is-invalid' :'';  ?>" 
                                 value="<?php echo $password; ?>">
                                 <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="remember-me" class="text-info"><span>Remember me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                            <div id="register-link" class="text-right">
                                <a href="register.php" class="text-info">Register here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>