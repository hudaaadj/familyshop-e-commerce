<?php
require_once 'connexion.php';



if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Check if the category exists
    $sqlCheck = $pdo->prepare('SELECT * FROM categorie WHERE id = ?');
    $sqlCheck->execute([$id]);
    $category = $sqlCheck->fetch();

    if ($category) {
        $sqlDelete = $pdo->prepare('DELETE FROM categorie WHERE id = ?');
        if ($sqlDelete->execute([$id])) {
            echo 'Catégorie supprimée avec succès.'; // Debugging statement
            header("Location: catégories.php"); // Redirect to categories page
            exit;
        } else {
            $errorInfo = $sqlDelete->errorInfo();
            echo 'Erreur lors de la suppression de la catégorie: ' . $errorInfo[2];
        }
    } else {
        echo 'Catégorie introuvable.';
    }
} else {
    echo 'ID non spécifié.';
}
?>

?>
