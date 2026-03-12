<?php
require_once 'connexion.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlState = $pdo->prepare('DELETE FROM contact WHERE id=?');
    $supprime = $sqlState->execute([$id]);
    header("location:".$_SERVER['HTTP_REFERER']);
}
?>

