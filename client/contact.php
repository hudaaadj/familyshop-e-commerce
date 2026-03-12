<?php
// Inclure le fichier de connexion à la base de données
require '../admin/connexion.php';
session_start();


// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $numtel = $_POST['numtel']; // Utiliser le bon nom de champ
    $message = $_POST['message'];

    // Préparer la requête d'insertion
    $sql = "INSERT INTO contact (nom, email, numtel, message) VALUES (:nom, :email, :numtel, :message)";

    // Préparer la déclaration SQL
    $stmt = $pdo->prepare($sql);

    // Liage des paramètres
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':numtel', $numtel); // Utiliser le bon nom de variable
    $stmt->bindParam(':message', $message);

    // Exécution de la requête
    if ($stmt->execute()) {
        echo "Les données ont été insérées avec succès.";
    } else {
        echo "Erreur lors de l'insertion des données: " . $stmt->errorInfo()[2];
    }
}
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
		<div class="titre">
			<h2>|</h2>
			<h3>Contacter nous
			</h3>
		</div> 
    <section id="form-details">
      <form   action="" method="POST">
        
        <input  type="text" id="nom" name="nom" required placeholder="Nom et Prénom">
    
        <input  type="email" id="email" name="email" required placeholder="email">
    
        <input  type="tel" id="tel" name="numtel" required placeholder="Num°tel">
    
      
    
        <textarea  id="message" name="message" rows="5" required placeholder="Votre message"></textarea>
    
        <button class="button" type="submit">Envoyer</button>
    
      </form>
     
    </section>
    <section id="contact-details" class="section-p1">
      <div class="details">
        <div>
          <li>
            <i class='bx bxs-map'></i>
            <p>Famili Shop Hypermarché Blida Zone Industrielle, Blida, Algeria</p>
          </li>
          <li>
            <i class='bx bxs-phone'></i>
            <p>025 32 50 11</p>
          </li>
          <li>
            <i class='bx bxs-envelope' ></i>
            <p>contact-marketing@familishop.net</p>
          </li>
          <li>
            <i class='bx bxl-facebook-square'></i>
            <p>Famili Shop</p>
          </li>
          <li>
            <i class='bx bxl-instagram-alt'></i>
            <p>Famili Shop</p>
          </li>
        </div>
      </div>
      <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3207.4818447155776!2d2.8410091745666075!3d36.494229184889434!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128f0bf6ad7e7a7f%3A0x6b8048db1aa96dc7!2sFamili%20Shop%20Hypermarch%C3%A9%20Blida!5e0!3m2!1sfr!2sdz!4v1716835731741!5m2!1sfr!2sdz" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

</body>


</html>

