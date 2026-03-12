<?php
require_once 'connexion.php';
session_start();



$idCommande = $_GET['id'];

// Mettre à jour l'état de la commande
$sqlState = $pdo->prepare('UPDATE commande SET etat = 1 WHERE id = ?');
$sqlState->execute([$idCommande]);

// Redirection vers la page des commandes
header('Location: commandes.php');
exit;
?>
