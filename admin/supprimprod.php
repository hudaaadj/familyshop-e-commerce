<?php
    require_once 'connexion.php';
 
    $id = $_GET['id'];
    $sqlState = $pdo->prepare('DELETE FROM produit WHERE id=?');
    $supprime = $sqlState->execute([$id]);
    header('location: produits.php');?>