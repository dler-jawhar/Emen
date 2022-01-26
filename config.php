<?php


try{

    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=emeniu', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


}catch(PDOException $e){
    die("ERROR: Culd not connect." .$e->getMessage());

}

?>