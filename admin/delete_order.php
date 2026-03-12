<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idCommande = $_POST['id'];

    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Supprimer les lignes de commande
        $sqlDeleteLigneCommande = 'DELETE FROM ligne_commande WHERE id_commande = ?';
        $stmtDeleteLigneCommande = $pdo->prepare($sqlDeleteLigneCommande);
        $stmtDeleteLigneCommande->execute([$idCommande]);

        // Supprimer la commande
        $sqlDeleteCommande = 'DELETE FROM commande WHERE id = ?';
        $stmtDeleteCommande = $pdo->prepare($sqlDeleteCommande);
        $stmtDeleteCommande->execute([$idCommande]);

        // Valider la transaction
        $pdo->commit();

        // Rediriger vers commandes.php avec un message de succès
        header('Location: commandes.php?message=Commande supprimée avec succès');
        exit;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        // Gérer l'erreur, éventuellement la journaliser et afficher un message d'erreur
        echo 'Erreur lors de la suppression de la commande: ' . $e->getMessage();
    }
} else {
    // Rediriger vers commandes.php si la méthode de requête n'est pas POST ou si l'id n'est pas défini
    header('Location: commandes.php');
    exit;
}
?>
