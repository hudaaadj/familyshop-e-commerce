<?php
require_once '../admin/connexion.php';
session_start();

$id = $_GET['id'];

// Récupérer les informations du produit
$sqlState = $pdo->prepare("SELECT * FROM produit WHERE id=?");
$sqlState->execute([$id]);
$produit = $sqlState->fetch(PDO::FETCH_ASSOC);
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
// Vérifiez si le produit existe
if ($produit) {
    // Récupérer les informations de la catégorie associée
    $idCategorie = $produit['id_categorie'];
    $sqlCategorie = $pdo->prepare("SELECT * FROM categorie WHERE id=?");
    $sqlCategorie->execute([$idCategorie]);
    $categorie = $sqlCategorie->fetch(PDO::FETCH_ASSOC);

    // Vérifiez si la catégorie existe
    if ($categorie) {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Marhey:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Produit | <?php echo htmlspecialchars($produit['libelle']); ?></title>
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
        <a href="">Cosmétique</a>
        <a href="">Articles bébé</a>
        <a href="">Electroménagers</a>
        <a href="">Vaisselles</a>
        <a href="">Linges de maisons</a>
      </ul>
    </div>

    
   
    <?php
if (isset($_SESSION['success_message'])) :
  ?>
  <div class="success-message" style="color: green; border: 1px solid green; padding: 10px;">
    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
  </div>
<?php elseif (isset($_SESSION['error_message'])) : ?>
  <div class="error-message" style="color: red; border: 1px solid red; padding: 10px;">
    <?php echo $_SESSION['error_message']; ?>
  </div>
<?php endif; ?>



    <section id="prodetails" class="secp1">
         <div class="single-pro-img">
            <?php $disponible = $produit['disponibilité'];
            if ($disponible == "Oui"){ ?>
               <p class="desponibilitetwo">Disponible</p>
            <?php } else { ?>
               <p class="nondesponibilitetwo">Non disponible</p>
            <?php } ?>
            <img src="../upload/produit/<?php echo $produit['image']; ?>" width="100%" height="400px" id="mainimg" alt="Image principale">
            <div class="small-img-grp">
            <div class="small-img-col">
        <img src="../upload/produit/<?php echo $produit['image']; ?>" width="100%" height="80px" class="smallimg">
            </div>

                 <?php for ($i = 1; $i <= 4; $i++) { ?>
              

                 <?php if (isset($produit['image' . $i])) { ?>
                  <div class="small-img-col">
                <img src="../upload/produit/<?php echo $produit['image' . $i]; ?>" width="100%" height="80px" class="smallimg">
            </div>
                 <?php } 
                 ?>

    
               <?php } ?>
           
           </div> 
         </div>
         <div class="single-pro-details">
            <h2><?php echo htmlspecialchars($produit['libelle']); ?></h2>
            <div class="prixtwo">
               <?php
                  $discount = $produit['discount'];
                  $prix = $produit['prix'];
                  if (!empty($discount)) {
               ?>
                  <h4 class="oldprixtwo"><strike><?php echo $prix; ?>Da</strike></h4>
                  <?php
                     $prixd = $prix - (($prix * $discount) / 100);
                  ?>
                  <h4 class="total"><?php echo $prixd; ?>Da</h4>
               <?php } else { ?>
                  <h4 class="total"><?php echo $prix; ?>Da</h4>
               <?php } ?>
            </div>

            <form method="post" action="ajouter-panier.php">
           
      <input type="hidden" name="id" value="<?php echo $produit['id']; ?>">
      <input type="hidden" name="disponibilite" value="<?php echo $produit['disponibilité']; ?>">
        <?php if ($categorie['mesure'] == "taille") { ?>
            <?php if (!empty($produit['mesuredisponible'])) { ?>
                <h4>Taille disponible: <?php echo $produit['mesuredisponible']; ?></h4>
            <?php } ?>
            <select name="mesure">
                <option value="XL">XL</option>
                <option value="L">L</option>
                <option value="XS">XS</option>
                <option value="S">S</option>
            </select>
        <?php } elseif ($categorie['mesure'] == "pointure") { ?>
            <?php if (!empty($produit['mesuredisponible'])) { ?>
                <h4>Pointure disponible: <?php echo $produit['mesuredisponible']; ?></h4>
            <?php } ?>
            <select name="mesure">
                <option value="35">35</option>
                <option value="36">36</option>
                <option value="37">37</option>
                <option value="38
                ">38</option>
                <option value="39">39</option>
                <option value="40">40</option>
                <option value="41">41</option>
                <option value="42">42</option>
                <option value="43">43</option>
                <option value="44">44</option>
            </select>
        <?php } ?>
        <label for="qty">Quantité</label>
        <input type="number" id="qty" value="0" min="0" name="qty">
        <button type="submit" class="normal" name="ajouter" <?php if ($produit['disponibilité'] != "Oui") echo 'disabled'; ?>>Ajouter au panier</button>
      </form>

      <h4>Détails du produit</h4>
      <span><?php echo htmlspecialchars($produit['description']); ?></span>
      
    </div>
    
  </section>
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
  <script>
    var mainimg = document.getElementById("mainimg");
    var smallimg = document.getElementsByClassName("smallimg");

    for (var i = 0; i < smallimg.length; i++) {
        smallimg[i].onclick = function() {
            mainimg.src = this.src;
        }
    }
  </script>

</body>
</html>
<?php
    } else {
        echo "Erreur : Catégorie non trouvée.";
    }
} else {
    echo "Erreur : Produit non trouvé.";
}
?>
