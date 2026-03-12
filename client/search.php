<?php
session_start();
require_once '../admin/connexion.php';

$searchQuery = $_GET['query'];
$sqlState = $pdo->prepare("SELECT * FROM produit WHERE libelle LIKE ?");
$sqlState->execute(["%$searchQuery%"]);
$searchResults = $sqlState->fetchAll(PDO::FETCH_OBJ);
if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
  $profileLink = "../profil/_profile.php";
} else {
  $profileLink = "../log/logfami.php";
}
if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
  $panierLink = "panier.php";
} else {
  $panierLink = "../log/logfami.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Marhey:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <title>Résultats de recherche pour "<?php echo htmlspecialchars($searchQuery); ?>"</title>
</head>
<body>
  <div class="header">
    <div class="container">
      <div class="gauche">
        <div class="logo">
          <a href="../index.php"><img class="logoimg" src="images/famillogo.PNG" alt=""></a>
        </div>
        <form action="search.php" method="GET">
          <input type="text" name="query" placeholder="Search" value="<?php echo htmlspecialchars($searchQuery); ?>">
        </form>  
      </div>
      <div class="icons">
     

  
     <a href="<?php echo $profileLink; ?>"><i class='bx bx-user-circle'></i>Profil</a>
     <?php if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])): ?>
       <?php
    $idUtilisateur=$_SESSION['utilisateur']['id'];
    ?>
         <a href="<?php echo $panierLink; ?>"><i class='bx bx-cart-alt'></i> Panier
   <span class="num"> <?php echo isset($_SESSION['panier'][$idUtilisateur]) ? count($_SESSION['panier'][$idUtilisateur]) : 0; ?></span> </a>
       <?php else: ?>
         <a href="<?php echo $panierLink; ?>"><i class='bx bx-cart-alt'></i> Panier</a>
       <?php endif; ?>
     </div>
    </div>
  </div>
  <div class="content">
    <div class="nav">
      <ul> 
        <a href="">Toutes Catégories</a>
        <a href="">Habillements</a>
        <a href="">Cosmétique</a>
        <a href="">Articles bébé</a>
        <a href="">Electroménagers</a>
        <a href="">Vaisselles</a>
        <a href="">Linges de maisons</a>
      </ul>
    </div>
    <div class="ttre">
      <h2>|</h2>
      <h3>Résultats de recherche pour "<?php echo htmlspecialchars($searchQuery); ?>"</h3>
    </div>
    <div class="imgs-container">
      <?php if (!empty($searchResults)): ?>
        <?php foreach ($searchResults as $produit): ?>
          <a href="produit.php?id=<?php echo $produit->id?>" class="box">
            <?php $disponible = $produit->disponibilité; ?>
            <?php if ($disponible == "Oui"): ?>
              <p class="desponibilite">Disponible</p>
            <?php else: ?>
              <p class="nondesponibilite">Non disponible</p>
            <?php endif; ?>
            <img src="../upload/produit/<?php echo $produit->image; ?>" alt="">
            <div class="description">
              <h4><?php echo $produit->libelle; ?></h4>
              <div class="prix">
                <?php
                $discount = $produit->discount;
                $prix = $produit->prix;
                if (!empty($discount)): ?>
                  <p class="oldprix"><strike><?php echo $prix ?> Da</strike></p>
                  <?php
                  $total = $prix - (($prix * $discount) / 100);
                  ?>
                  <p class="total"><?php echo $total ?> Da</p>
                <?php else: ?>
                  <p class="total"><?php echo $prix ?> Da</p>
                <?php endif; ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Aucun résultat trouvé</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

