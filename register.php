<?php

session_start();

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=emeniu', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$name_err = '';
$password_err = '';
$confirmPass_err = '';
$username = '';
$password = '';
$password_conf = '';
function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {


 
    

    if (empty(trim($_POST['username']))) {
        $name_err = 'please enter a name';
    } else {
        if (preg_match("/^([a-zA-Z'0-9_]+)$/", trim($_POST['username']))) {
            $stm = $pdo->prepare('SELECT id FROM users WHERE name = :username ');
            $stm->bindValue(':username', trim($_POST['username']));
            $stm->execute();
            $sameName = $stm->fetch(PDO::FETCH_ASSOC);
            unset($stm);
            if (empty($sameName)) {
                $username = trim($_POST['username']);
            }else {
                $name_err = 'the name is taken try another name';
            } 
        } else {
            $name_err = 'the name must contain only letters, numbers and uderspace';

        }


        

        if (empty(trim($_POST['password']))) {

            $password_err = 'please enter a passwrod';
        } 
        else {
            if (strlen($_POST['password']) < 6 && !empty(trim($_POST['password']))) {
                $password_err = 'the password must have at least 6 charechters';
            } else {
                $password = $_POST['password'];
            }
        }
        if (empty($password_err)) {
            if (empty($_POST['confirm_password'])) {
                $confirmPass_err = "please confirm the password";
            } 
            elseif($password != $_POST['confirm_password']) {
                    $confirmPass_err = 'the password dose not match the cofirm password';
                }
                
            else {
                    $password_conf = trim($_POST['confirm_password']);
             
                }
         }
     

        if (empty($confirmPass_err) && empty($name_err) && empty($password_err)) {


            var_dump($_FILES);
            $pimage=$_FILES['pimage'];
             if(!is_dir('pimages')){
                 mkdir('pimages');
             }
             if($pimage && $pimage['tmp_name']){
                $imagepath='pimages/'. randomString(8) . '/' . $pimage['name'];
            
                 mkdir(dirname(($imagepath)));
                  move_uploaded_file($pimage['tmp_name'],$imagepath);
                }


            $parm_password = password_hash($password, PASSWORD_DEFAULT);
            $parm_user = $username;
            $stm = $pdo->prepare('INSERT INTO users(name,password,pimage) values(:username,:password, :pimage)');
            $stm->bindValue(':username', $parm_user);
            $stm->bindValue(':password', $parm_password);
            $stm->bindValue(':pimage', $imagepath);
            $stm->execute();

            unset($stm);
            header('Location: logIn.php ');
        } else {
            $errs = 'somthing went wrong try again';
        }
    }


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

    <div id="login">
        <h3 class="text-center text-white pt-5">Register form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="post">
                            <h3 class="text-center text-info">Register</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"" value=" <?php echo $username ?>">
                                <span class="invalid-feedback"><?php echo $name_err ?></span>

                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="text" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo '' ?> ">
                                <span class="invalid-feedback"><?php echo $password_err ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">confirm Password:</label><br>
                                <input type="text" name="confirm_password" class="form-control <?php echo (!empty($confirmPass_err)) ? 'is-invalid' : ''; ?>" value="<?php echo '' ?> ">
                                <span class="invalid-feedback"><?php echo $confirmPass_err ?></span>
                            </div>
                            <div class="form-group">
                                <label for="file" class="text-info">Image</label>
                                <input type="file" name='pimage' id='pimage' class="form-control">
                                
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Register">
                                <input type="reset" name="reset" class="btn btn-secondary ml-2 btn-md" value="Reset">
                            </div>
                            

                            <div id="register-link" class="text-left">
                                <p>already have an account? <a href="login.php" class="text-info">login here</a></p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>