<?php
require_once 'connexion.php';


// Vérifier si la méthode de requête est POST et si l'identifiant de la ligne de commande est défini
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_ligne_commande'])) {
    $idLigneCommande = $_POST['id_ligne_commande'];

    try {
        // Récupérer l'identifiant de la commande avant de supprimer la ligne de commande
        $sqlGetCommandeId = 'SELECT id_commande FROM ligne_commande WHERE id = ?';
        $stmtGetCommandeId = $pdo->prepare($sqlGetCommandeId);
        $stmtGetCommandeId->execute([$idLigneCommande]);
        $idCommande = $stmtGetCommandeId->fetchColumn();

        // Supprimer la ligne de commande de la base de données
        $sqlDeleteLigneCommande = 'DELETE FROM ligne_commande WHERE id = ?';
        $stmtDeleteLigneCommande = $pdo->prepare($sqlDeleteLigneCommande);
        $stmtDeleteLigneCommande->execute([$idLigneCommande]);

        // Recalculer le total de la commande
        $sqlGetTotalCommande = 'SELECT SUM(prix * quantité) AS total FROM ligne_commande WHERE id_commande = ?';
        $stmtGetTotalCommande = $pdo->prepare($sqlGetTotalCommande);
        $stmtGetTotalCommande->execute([$idCommande]);
        $nouveauTotal = $stmtGetTotalCommande->fetchColumn();

        // Mettre à jour le total de la commande dans la base de données
        $sqlUpdateTotalCommande = 'UPDATE commande SET total = ? WHERE id = ?';
        $stmtUpdateTotalCommande = $pdo->prepare($sqlUpdateTotalCommande);
        $stmtUpdateTotalCommande->execute([$nouveauTotal, $idCommande]);

        // Vérifier si la commande est vide
        $sqlCheckCommandeVide = 'SELECT COUNT(*) FROM ligne_commande WHERE id_commande = ?';
        $stmtCheckCommandeVide = $pdo->prepare($sqlCheckCommandeVide);
        $stmtCheckCommandeVide->execute([$idCommande]);
        $nombreLignes = $stmtCheckCommandeVide->fetchColumn();

        if ($nombreLignes == 0) {
            // Supprimer la commande si elle est vide
            $sqlDeleteCommande = 'DELETE FROM commande WHERE id = ?';
            $stmtDeleteCommande = $pdo->prepare($sqlDeleteCommande);
            $stmtDeleteCommande->execute([$idCommande]);
        }

        // Rediriger vers commandes.php avec un message de succès
        header('Location: commandes.php?message=Ligne de commande supprimée avec succès');
        exit;
    } catch (Exception $e) {
        // Gérer l'erreur
        echo 'Erreur lors de la suppression de la ligne de commande: ' . $e->getMessage();
    }
} else {
    // Rediriger vers commandes.php si l'identifiant de la ligne de commande n'est pas défini
    header('Location: commandes.php');
    exit;
}
?>







