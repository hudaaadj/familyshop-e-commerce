<?php
session_start();

// Connexion à la base de données
require_once '../admin/connexion.php';

$signup_error = '';
$login_error = '';

// Vérifier si le formulaire de connexion a été soumis
if (isset($_POST['login'])) {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier les identifiants de connexion dans la base de données
    $login_query = "SELECT * FROM utilisateur WHERE email=:email";
    $login_stmt = $pdo->prepare($login_query);
    $login_stmt->bindParam(':email', $email);
    $login_stmt->execute();

    if ($login_stmt->rowCount() == 1) {
        // Récupérer les informations de l'utilisateur
        $user = $login_stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier le mot de passe saisi avec le hachage stocké
        if (password_verify($password, $user['password'])) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['utilisateur'] = $user;

            // Rediriger l'utilisateur vers la page appropriée
            if ($user['role'] == 'admin') {
                header("Location:../admin/produits.php");
                exit; // Assurez-vous de quitter le script après la redirection
            } else {
                header("Location:../index.php");
                exit; // Assurez-vous de quitter le script après la redirection
            }
        } else {
            $login_error = "Mot de passe incorrect.";
        }
    } else {
        $login_error = "Email ou mot de passe incorrect.";
    }
}

// Vérifier si le formulaire d'inscription a été soumis
if (isset($_POST['signup'])) {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $prenom = $_POST['prenom'];
    $adress = $_POST['adress'];
    $numtel = $_POST['numtel'];
    $role = 'client'; // Par défaut, le rôle est client
    
    // Vérifier si l'utilisateur existe déjà
    $check_query = "SELECT * FROM utilisateur WHERE email=:email";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 0) {
        $signup_error = "L'utilisateur existe déjà.";
    } else {
        // Insérer l'utilisateur dans la base de données
        $insert_query = "INSERT INTO utilisateur (nom, email, password, prenom, adress, numtel, role) VALUES (:nom, :email, :password, :prenom, :adress, :numtel, :role)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->bindParam(':nom', $nom);
        $insert_stmt->bindParam(':email', $email);
        $insert_stmt->bindParam(':password', $password);
        $insert_stmt->bindParam(':prenom', $prenom);
        $insert_stmt->bindParam(':adress', $adress);
        $insert_stmt->bindParam(':numtel', $numtel);
        $insert_stmt->bindParam(':role', $role);
        
        if ($insert_stmt->execute()) {
            // Stocker les informations de l'utilisateur dans la session
            $id = $pdo->lastInsertId(); // Récupérer l'ID de l'utilisateur nouvellement inséré
            $_SESSION['utilisateur'] = array(
                'id' => $id,
                'nom' => $nom,
                'email' => $email,
                'prenom' => $prenom,
                'adress' => $adress,
                'numtel' => $numtel,
                'role' => $role
            );

            header("Location:../index.php");
            exit; // Assurez-vous de quitter le script après la redirection
        } else {
            $signup_error = "Erreur lors de l'inscription.";
        }
    }
}

// Déterminer les liens de profil et de panier
$profileLink = isset($_SESSION['utilisateur']) ? "../profil/_profil.php" : "#";
$panierLink = isset($_SESSION['utilisateur']) ? "../client/panier.php" : "#";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="login1.css">
    <title>signin-signup</title>
    <style>
        .error-message {
            color: red;
            margin-top:70px;
            font-size: 14px; /* Taille de la police */
            margin-right:150px;;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="gauche">
                <div class="logo">
                    <a href="../index.php"><img class="logoimg" src="../client/images/famillogo.PNG" alt=""></a>
                </div>
                <form action="#">
                    <input type="text" placeholder="Search">
                </form>  
            </div>
            <div class="icons">
                <a href="<?php echo $profileLink; ?>"><i class='bx bx-user-circle'></i>Profil</a>
                <?php if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])): ?>
                    <?php $idUtilisateur = $_SESSION['utilisateur']['id']; ?>
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
        <div class="containerr">
            <div class="signin-signup">
                <form action="" class="sign-in-form" method='post'>
                    <h2 class="title">Connexion</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="email" placeholder="Entrer votre email" required name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <?php if (!empty($login_error)): ?>
                        <?php endif; ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Entrer votre mot de passe" required name="password">
                        <?php if (!empty($login_error)): ?>
                            <p class="error-message"><?php echo $login_error; ?></p>
                        <?php endif; ?>
                    </div>
                    <input type="submit" value="connexion" class="btn" name="login">
                    <p class="account-text"> Vous n'avez pas un compte? <a href="#" id="sign-up-btn2">Inscrit</a></p>
                </form>
                <form action="" class="sign-up-form" method='post'>
                    <h2 class="title">Inscription</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Entrer votre nom" required name="nom" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Entrer votre prénom" required name="prenom" value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Entrer votre email" required name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <?php if (!empty($signup_error)): ?>
                            <p class="error-message"><?php echo $signup_error; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Entrer votre mot de passe" required name="password">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Entrer votre adress" required name="adress" value="<?php echo isset($_POST['adress']) ? htmlspecialchars($_POST['adress']) : ''; ?>">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="number" placeholder="Enter votre Num°tel" required name="numtel" value="<?php echo isset($_POST['numtel']) ? htmlspecialchars($_POST['numtel']) : ''; ?>">
                    </div>
                    <input type="submit" value="Inscription" class="btn" name="signup">
                    <p class="account-text"> Vous avez un compte? <a href="#" id="sign-in-btn2">Connecter</a></p>
                </form>
            </div>
            <div class="panels-containerr">
                <div class="panel left-panel">
                    <div class="contentt">
                        <h3> Member of Brand ?</h3>
                        <p> Bienvenue sur notre boutique en ligne</p>
                        <button class="btn" id="sign-in-btn">Connecter</button>
                    </div>
                    <img src="../client/images/log.png" alt="" class="image">
                </div>
                <div class="panel right-panel">
                    <div class="contentt">
                        <h3> Nouveau Membre ?</h3>
                        <p> Bienvenue sur notre boutique en ligne</p>
                        <button class="btn" id="sign-up-btn">Inscription</button>
                    </div>
                    <img src="../client/images/log.png" alt="" class="image">
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
                <a href="panier.php">Voir Panier</a>
                <a href="../profil/_profile.php">Voir Profil</a>
                <a href="contact.php">Contacte</a>
            </div>
        </footer>
    </div>
    <script src="login.js"></script>
</body>
</html>
