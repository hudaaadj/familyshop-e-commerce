<!DOCTYPE html>
<?php
require_once 'admin/connexion.php';
session_start();

// Sélectionnez 12 produits aléatoires dans la base de données
$sqlState = $pdo->prepare("SELECT * FROM produit ORDER BY RAND() LIMIT 12");
$sqlState->execute();
$produits = $sqlState->fetchAll(PDO::FETCH_ASSOC);

$sqlNewProducts = $pdo->prepare("SELECT * FROM produit ORDER BY date_creation DESC LIMIT 12");
$sqlNewProducts->execute();
$newProducts = $sqlNewProducts->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
  $profileLink = "profil/_profile.php";
} else {
  $profileLink = "log/logfami.php";
}
if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
  $panierLink = "client/panier.php";
} else {
  $panierLink = "log/logfami.php";
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Marhey:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="client/style.css">
  <title>FamiliShop</title>
</head>
<body>
  <div class="header">
    <div class="container">
      <div class="gauche">
        <div class="logo">
          <a href=""><img class="logoimg" src="client/images/famillogo.PNG" alt=""></a>
        </div>
        <form action="client/search.php" method="get">
          <input type="text" name="query" placeholder="Search">
        </form>
      </div>
      
      <div class="icons">
        <a href="<?php echo $profileLink; ?>"><i class='bx bx-user-circle'></i>Profil</a>
        <?php if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])): ?>
          <?php $idUtilisateur = $_SESSION['utilisateur']['id']; ?>
          <a href="<?php echo $panierLink; ?>"><i class='bx bx-cart-alt'></i> Panier
            <span class="num"> <?php echo isset($_SESSION['panier'][$idUtilisateur]) ? count($_SESSION['panier'][$idUtilisateur]) : 0; ?></span>
          </a>
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
  
    <!-- Slider home -->
    <div id="wrapper">
      <img class="image image1" src="client/images/fm.png" alt="">
      <img class="image image2" src="client/images/fm2.png" alt="">
      <img class="image image3" src="client/images/bebeprin.png" alt="">
      <div id="leftbtn" onclick="left()">&#10094;</div>
      <div id="rightbtn" onclick="right()">&#10095;</div>
      <div class="box2">
        <div class="btn" onclick="selectImage(0)"></div>
        <div class="btn" onclick="selectImage(1)"></div>
        <div class="btn" onclick="selectImage(2)"></div>
      </div>
    </div>
  
    <!-- CATEGORIES -->
    <div class="catégories">
      <div class="titre">
        <h2>|</h2>
        <h3>Catégories</h3>
      </div>

      <div id="index">
        <?php
          $categories = $pdo->query("SELECT * FROM categorie LIMIT 11")->fetchAll(PDO::FETCH_OBJ);
        ?>
        <div class="index">
          <?php foreach($categories as $categorie): ?>
            <a href="client/categorie.php?id=<?php echo $categorie->id?>" class="index-box">
              <img src="upload/catégorie/<?php echo $categorie->image?>" alt="">
              <p><?php echo $categorie->libelle?></p>
            </a>
          <?php endforeach; ?>
          <a href="client/cat.php" class="index-plus">
            <i class='bx bx-plus'></i>
            <p>voir plus</p>
          </a>
        </div>
      </div>
    </div>
  
    <!-- Products -->
    <div class="produit">
      <div class="titre">
        <h2>|</h2>
        <h3>Tout Les Produits</h3>
      </div>
      <div class="imgs-container">
        <?php foreach ($produits as $produit): ?>
          <a href="client/produit.php?id=<?php echo $produit['id']?>" class="box">
            <?php $disponible = $produit['disponibilité']; ?>
            <p class="<?php echo $disponible == 'Oui' ? 'desponibilite' : 'nondesponibilite'; ?>">
              <?php echo $disponible == 'Oui' ? 'disponible' : 'non disponible'; ?>
            </p>
            <img src="upload/produit/<?php echo $produit['image']; ?>" alt="">
            <div class="description">
              <h4><?php echo $produit['libelle']; ?></h4>
              <div class="prix">
                <?php
                  $discount = $produit['discount'];
                  $prix = $produit['prix'];
                  if (!empty($discount)) {
                    echo '<p class="oldprix"><strike>' . $prix . 'Da</strike></p>';
                    $total = $prix - (($prix * $discount) / 100);
                    echo '<p class="total">' . $total . 'Da</p>';
                  } else {
                    echo '<p class="total">' . $prix . 'Da</p>';
                  }
                ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="afficher-plus">
        <a href="client/tousproduits.php"><p>Afficher plus</p></a>
      </div>

      <div class="titre">
        <h2>|</h2>
        <h3>Nouveauté</h3>
      </div>
      
      <div class="imgs-container">
        <?php foreach ($newProducts as $produit): ?>
          <a href="client/produit.php?id=<?php echo $produit['id']?>" class="box">
            <?php $disponible = $produit['disponibilité']; ?>
            <p class="<?php echo $disponible == 'Oui' ? 'desponibilite' : 'nondesponibilite'; ?>">
              <?php echo $disponible == 'Oui' ? 'disponible' : 'non disponible'; ?>
            </p>
            <img src="upload/produit/<?php echo $produit['image']; ?>" alt="">
            <div class="description">
              <h4><?php echo $produit['libelle']; ?></h4>
              <div class="prix">
                <?php
                  $discount = $produit['discount'];
                  $prix = $produit['prix'];
                  if (!empty($discount)) {
                    echo '<p class="oldprix"><strike>' . $prix . 'Da</strike></p>';
                    $total = $prix - (($prix * $discount) / 100);
                    echo '<p class="total">' . $total . 'Da</p>';
                  } else {
                    echo '<p class="total">' . $prix . 'Da</p>';
                  }
                ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    
    <footer class="section-p1" style="background-color: #fff;">
      <div class="col">
        <img class="logg" src="client/images/logo.jpg" alt="">
        <h4>Contact</h4>
        <p><strong>Adress:</strong> Famili Shop Hypermarché Blida Zone Industrielle, Blida, Algeria</p>
        <p><strong>Tel°:</strong> 025 32 50 11</p>
        <p><strong>Email:</strong> contact-marketing@familishop.net</p>
        <div class="follow">
          <h4>Suivez-nous</h4>
          <div class="iconn">
            <i class='bx bxl-facebook-square'></i>
            <i class='bx bxl-instagram-alt'></i>
          </div>
        </div>
      </div>
      <div class="col2">
        <h4>Compte</h4>
        <a href="log/logfami.php">Inscription</a>
        <a href="client/panier.php">Voir Panier</a>
        <a href="profil/_profile.php">Voir Profil</a>
        <a href="client/contact.php">Contacte</a>
      </div>
    </footer>
  </div>

  <script src="client/scipt.js"></script>
</body>
</html>

