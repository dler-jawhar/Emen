<?php

session_start();



$id=$_GET['id'] ?? null;

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=EMeniu', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stm = $pdo->prepare('SELECT * FROM foods WHERE user_id = :id ');

$us_id= $_SESSION['id'] ?? null;
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']=== true){
$id=$_SESSION['id'];
}
else{
    $id=$_GET['id'] ?? null;
}



$stm->bindValue(':id',$id);
$stm->execute();


$foods = $stm->fetchAll(PDO::FETCH_ASSOC);

session_unset();

//setcookie($name=$food['Name'],$description=$food['Description'],$price=$food['Price'],$image=$food['Image'])
?>
<!-- todoes

--show each user different conttent,meniu.
--make website more atractive by adding javascirpt to images when togleing on them 
they shuld zoom in.
--add multi language opption and set language section to right hand side 
-- add downlodable QR code generator for each meniu 
--publish the website on a server  



-->
<!doctype html>
<html lang="en">

<head>
    <title>Home</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <?php require("navbar.php"); ?>

    <div class="container">
        <div class="row">

            <?php foreach ($foods as $food) { ?>
                <div class="col-sm" style="margin-top: 30px;">

                    <div class="card " style="width: 18rem;">
                        <?php if ($food['Image']) : ?>

                            <img class="card-img-top" src="<?php echo $food['Image'] ?>" alt="Card image cap">
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo $food['Name'] ?></h5>
                            <div class="overflow-auto">
                                <p class="card-text"><?php echo $food['Description'] ?></p>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><?php echo $food['Price'] ?> $ </li>


                        </ul>
                        <div class="dropdown d-grid gap-2">
                            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                Options
                            </button>
                            <ul class="dropdown-menu " aria-labelledby="dropdownMenuButton2">
                                <li>
                                    <form method="post" action="Delete.php" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $food['Id'] ?>">
                                        <button type="submit" class="dropdown-item">Delete</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="Edit.php" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $food['Id'] ?>">
                                        <button type="submit" class="dropdown-item">Edit</button>
                                    </form>
                                </li>

                            </ul>
                        </div>

                    </div>
                </div>

            <?php } ?>

        </div>
    </div>

</body>

</html>