<?php

$pdo=new PDO('mysql:host=localhost;port=3306;dbname=Emeniu', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$stm= $pdo->prepare('SELECT * FROM users ');

$stm->execute();


$restu=$stm->fetchAll(PDO::FETCH_ASSOC);





?>
<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
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

        <?php foreach ($restu as $res) { ?>
            <div class="col-sm" style="margin-top: 30px;">
                
                <div >
                <form action="home.php" method="get">
                    <input hidden name="id" value="<?php echo $res['id'] ?>">
                    <button class="card " style="width: 18rem;" type="submit">
                    <?php if ($res['pimage']) : ?>

                        <img class="card-img-top" src="<?php echo $res['pimage'] ?>" alt="Card image cap">
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title"><?php echo $res['name'] ?></h5>
                       
                    </div>
                    </button>
                   
                </form>
                </div>
            </div>

        <?php } ?>

    </div>
</div>



</body>
</html>