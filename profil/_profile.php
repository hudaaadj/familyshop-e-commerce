<?php
require_once '../admin/connexion.php';
session_start();
$id = $_SESSION['utilisateur']['id'];
if (!isset($id)) {
    header('location:../log/logfami.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $numtel = $_POST['numtel'];
    $adress = $_POST['adress'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sqlUpdate = $pdo->prepare("UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, numtel = :numtel, adress = :adress,password = :password WHERE id = :id");
    $sqlUpdate->bindParam(':nom', $nom);
    $sqlUpdate->bindParam(':prenom', $prenom);
    $sqlUpdate->bindParam(':email', $email);
    $sqlUpdate->bindParam(':numtel', $numtel);
    $sqlUpdate->bindParam(':adress', $adress);
    $sqlUpdate->bindParam(':password', $password);

    $sqlUpdate->bindParam(':id', $id);
    $sqlUpdate->execute();
}

$sqlState = $pdo->prepare("SELECT * FROM utilisateur WHERE id=:id");
$sqlState->bindParam(':id', $id);
$sqlState->execute();

if ($sqlState->rowCount() > 0) {
    $fetch = $sqlState->fetch(PDO::FETCH_ASSOC);
}
if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
    $profileLink = "#";
  } else {
    $profileLink = "../log/logfami.php";
  }
  if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
    $panierLink = "../client/panier.php";
  } else {
    $panierLink = "../log/logfami.php";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Account Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="-profile.css" rel="stylesheet">
    <style type="text/css">
        body { margin: 0; padding-top: 40px; color: #2e323c; background: #f5f6fa; position: relative; height: 100%; }
        .account-settings .user-profile {
            margin: 0 0 1rem 0;
            padding-bottom: 1rem;
            text-align: center;
        }
        .account-settings .user-profile .user-avatar {
            margin: 0 0 1rem 0;
        }
        .account-settings .user-profile .user-avatar img {
            width: 90px;
            height: 90px;
            border-radius: 100px;
        }
        .account-settings .user-profile h5.user-name {
            margin: 0 0 0.5rem 0;
        }
        .account-settings .user-profile h6.user-email {
            margin: 0;
            font-size: 0.8rem;
            font-weight: 400;
            color: #9fa8b9;
        }
        .account-settings .about {
            margin: 2rem 0 0 0;
            text-align: center;
        }
        .account-settings .about h5 {
            margin: 0 0 15px 0;
            color: #e91313;
        }
        .account-settings .about p {
            font-size: 0.825rem;
        }
        .form-control {
            border: 1px solid #cfd1d8;
            border-radius: 2px;
            font-size: .825rem;
            background: #ffffff;
            color: #2e323c;
        }
        .card {
            background: #ffffff;
            border-radius: 5px;
            border: 0;
            margin-bottom: 1rem;
        }
        footer{
    display: flex;
    justify-content: space-between;
    margin-top:100px;
  }
  footer .col{
    margin-left: 2%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 20px;
  }
  footer .col2{
    margin-left: 2%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 20px;
    margin-right: 20%;
    margin-top: 35px;
  
  }
  footer .logg{
    width: 50px;
    margin-top: 10px;
  }
  footer h4{
    font-size: 20px;
    color: #222;
  }
  footer p{
    font-size: 15px;
    margin: 0 0 8px 0;
    color: #222222e1;
  }
  footer a{
    font-size: 15px;
    text-decoration: none;
    color: #222222c4;
    text-transform: capitalize;
  }
  

    </style>
</head>
<body>
<div class="header">
    <div class="containere">
        <div class="gauche">
            <div class="logo">
                <a href="../index.php"><img class="logoimg" src="famillogo.PNG" alt=""></a>
            </div>
            <form action="">
                <input type="text" placeholder="Search">
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
        </ul>
    </div>
    <br><br>
    <div class="container">
        <div class="row gutters">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="account-settings">
                            <div class="user-profile">
                                <div class="user-avatar">
                                    <img src="profileimage.webp" alt="User Avatar">
                                </div>
                                <h5 class="user-name"><?php echo $fetch['nom']?> <?php echo $fetch['prenom']?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fullName">Nom</label>
                                        <input type="text" class="form-control" id="fullName" name="nom" placeholder="Enter full name" value="<?php echo $fetch['nom']?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="prenom">Prenom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Enter first name" value="<?php echo $fetch['prenom']?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="eMail">Email</label>
                                        <input type="email" class="form-control" id="eMail" name="email" placeholder="Enter email ID" value="<?php echo $fetch['email']?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Num°tel</label>
                                        <input type="text" class="form-control" id="phone" name="numtel" placeholder="Enter phone number" value="<?php echo $fetch['numtel']?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="ciTy">Adress</label>
                                        <input type="text" class="form-control" id="ciTy" name="adress" placeholder="Enter City" value="<?php echo $fetch['adress']?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                 <label for="password">Mot de passe</label>
                                 <input type="password" class="form-control" id="password" name="password" placeholder="Modifier votre mot de passe">
                               </div>

                                </div>
                                
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                        <button type="submit" id="submit" name="submit" class="btn btn-light">Modifier</button>
                                        <a href="deconnexion.php"><button type="button" id="submit" name="submit" class="btn btn-danger">Deconnexion</button></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="section-p1" style="background-color: #fff;">
        <div class="col">
          <img class="logg" src="../client/images/logo.jpg" alt="">
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
          <a href="../client/panier.php">Voir Panier</a>
          <a href="#">Voir Profil</a>
          <a href="../client/contact.php">Contacte</a>
        </div>
      
       </footer>
</div>

</body>
</html>
