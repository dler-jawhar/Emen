<?php 
$pdo= new PDO('mysql:host=localhost;port=3306;dbname=emeniu','root','');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$id=$_POST['id'] ?? null;
if(!$id)
{
    header('Location: home.php');
    exit;
}

$stm=$pdo->prepare('DELETE FROM foods WHERE Id= :id');
$stm->bindValue(':id',$id);
$stm->execute();

header('Location: home.php');

?>










