<?php

session_start();

if(!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] === true){
header('location: home.php'); 
}

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
$pdo=new PDO('mysql:host=localhost;port=3306;dbname=EMeniu','root','');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$errors=[];



if($_SERVER['REQUEST_METHOD']=='POST')
{
    $fName=$_POST['foodName'];
    $fDescrip=$_POST['description'];
    $fPrice=$_POST['foodPrice'];
    $image=$_FILES['foodImage'];
    if(!is_dir('images')){
        mkdir('images');
    }
    if($image && $image['tmp_name']){
        $imagePath='images/'. randomString(8) . '/' . $image['name'];
   mkdir(dirname(($imagePath)));
   move_uploaded_file($image['tmp_name'],$imagePath);
       
    }
    if(!$fName)
    {
        $errors[]='food name is required ';
    }
    if(!$fDescrip){
        $errors[]='food description is required';
    }
    if(empty($errors))
    {
        $stm=$pdo->prepare("INSERT INTO  foods (Name, Description, Price, Image,user_id)
        VALUES(:name, :descr,:price, :Image, :user_id)
        ");
        $stm->bindValue('name',$fName);
        $stm->bindValue('descr',$fDescrip);
        $stm->bindValue('price',$fPrice);
        $stm->bindValue('Image',$imagePath);
        $stm->bindValue(':user_id', $_SESSION['id']);


        $stm->execute();
        header('Location: home.php');
    }


}


?>

<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" type="text/css" >

  </head>
  <body>
      <?php require("navbar.php")?>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div> <?php echo $error ?></div>
                <?php endforeach;?>
        </div>
        <?php endif; ?>


    <form method="post" enctype="multipart/form-data"  class="fm">
        
    <h3>Crate New Item </h3>
    <div class="form-group " >
    <label for="image">Image</label><br>
    <input type="file"  class="form-control  id="image"  name="foodImage" >
  </div>
  <div class="form-group " >
    <label for="namefood">Food Name</label>
    <input type="text" class="form-control" id="namefood"  name="foodName" placeholder="food name">
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" name="description"  id="description" placeholder="Description">
  </div>
  <div class="form-group">
    <label for="Price">Price</label>
    <input type="text" class="form-control" name="foodPrice"  id="pricefood" placeholder="Price">
  </div>
  <button type="submit" class="btn btn-primary">Create</button>
</form>

</body>
</html>