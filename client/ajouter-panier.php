<?php
session_start();
require_once '../admin/connexion.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header('location:../log/logfami.php');
    exit;
}

$id = $_POST['id'];
$qty = $_POST['qty'];
$idUtilisateur = $_SESSION['utilisateur']['id'];

// Vérifiez si la clé 'mesure' existe dans $_POST
$mesure = isset($_POST['mesure']) ? $_POST['mesure'] : "";

// Vérifiez la disponibilité du produit
$sql = "SELECT disponibilité FROM produit WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if ($produit['disponibilité'] === 'Non') {
   
    $_SESSION['error_message'] = 'Ce produit n\'est pas disponible pour le moment.';

} else {
    if (!isset($_SESSION['panier'][$idUtilisateur])) {
        $_SESSION['panier'][$idUtilisateur] = [];
    }
    $_SESSION['panier'][$idUtilisateur][$id] = [
        'qty' => $qty,
        'mesure' => $mesure
    ];
    $_SESSION['success_message'] = 'Produit ajouté au panier avec succès!';
}

header("location:".$_SERVER['HTTP_REFERER']);
exit;
?>










