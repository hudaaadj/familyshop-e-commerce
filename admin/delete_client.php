<?php
require_once 'connexion.php';


if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    // Prepare and execute the delete statement
    $deleteSql = "DELETE FROM utilisateur WHERE id = :id";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->bindParam(':id', $clientId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect to the clients list page after deletion
        header('Location: client.php');
        exit;
    } else {
        echo "Error deleting record.";
    }
} else {
    echo "Invalid request.";
}
?>
