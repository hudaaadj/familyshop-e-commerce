<!DOCTYPE html>
<?php
session_start();

require_once '../admin/connexion.php';


$sqlState=$pdo->prepare("SELECT * FROM produit");
$sqlState->execute();
$produits=$sqlState->fetchAll(PDO::FETCH_OBJ);
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
  <title>Tous les produits</title>
</head>
<body>

  
    
  
  <div class="header">
    <div class="container">
      <div class="gauche">
        <div class="logo">
          <a href="../index.php"><img class="logoimg" src="images/famillogo.PNG" alt=""></a>
        </div>
        <form action="search.php" method="get">
  <input type="text" name="query" placeholder="Search">
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
        <a href=""> Cosmétique</a>
        <a href="">Articles bébé</a>
        <a href="">Electroménagers</a>
        <a href="">Vaisselles</a>
        <a href="">Linges de maisons</a>
      </ul>
    </div>
 <div class="ttre">
    <h2>|</h2>
    <h3>Tous les produits</h3>
  </div>
  <div class="imgs-container">
      <?php
         foreach ($produits as $produit){
          ?>
           <a href="produit.php?id=<?php echo $produit->id?>" class="box">
           <?php $disponible= $produit->disponibilité ;
         if ($disponible == "Oui"){ ?>
         <p class="desponibilite">disponible</p>
          <?php } else { ?>
        <p class="nondesponibilite">non disponible</p>
       <?php } ?>
              <img src="../upload/produit/<?php echo $produit->image?>" alt="">
              <div class="description">
            <h4><?php echo $produit->libelle; ?></h4>
            <div class="prix">
            
            <?php
            
              $discount=$produit->discount;
              $prix=$produit->prix;
             
              if(!empty($discount)){
                ?>
                <p class=oldprix> <strike><?php echo $prix ?>Da</strike></p>
                <?php
                $total=$prix-(($prix*$discount)/100);
                ?>
                 <p class="total"><?php echo $total ?>Da</p>
                <?php
              }else{
                $total=$prix;
                ?>
                <p class="total"><?php echo $total ?>Da</p>
                <?php
              }
            ?>
           
            </div>
            
          </div>
         </a>
     <?php
         }
      ?>


  </div>
  <footer class="section-p1" style="background-color: #fff;">
  <div class="col">
    <img class="logg" src="images/logo.jpg" alt="">
    <h4>Contact</h4>
    <p><strong>Adress:</strong> Famili Shop Hypermarché Blida Zone Industrielle, Blida, Algeria</p>
    <p><strong>Tel°:</strong> 025 32 50 11</p>
    <p><strong>Email:</strong>contact-marketing@familishop.net</p>
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
    <a href="../log/logfami.php">Inscription</a>
    <a href="panier.php">Voir Panier</a>
    <a href="../profil/_profile.php">Voir Profil</a>
    <a href="contact.php">Contacte</a>
  </div>

 </footer>
 </div>
 
</body>
</html>