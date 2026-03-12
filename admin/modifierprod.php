<?php
require_once 'connexion.php';


if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $libelle = $_POST['libelle'];
    $prix = $_POST['prix'];
    $discount = $_POST['discount'];
    $categorie = $_POST['categorie'];
    $quantité = $_POST['quantité'];
    $mesuredisponible = $_POST['mesuredisponible'];
    $description = $_POST['description'];
    $disponible = ($quantité > 0) ? "Oui" : "Non";


    $imageFields = ['image', 'image2', 'image3', 'image4'];
    $images = [];

    // Verify if new images are uploaded
    foreach ($imageFields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $filename = uniqid() . $_FILES[$field]['name'];
            if (move_uploaded_file($_FILES[$field]['tmp_name'], '../upload/produit/' . $filename)) {
                $images[$field] = $filename;
            } else {
                $images[$field] = $_POST['current_' . $field]; // use current image if upload fails
            }
        } else {
            $images[$field] = $_POST['current_' . $field]; // use current image if no new upload
        }
    }

    // Update product data in the database
    $query = "UPDATE produit SET libelle=?, prix=?, discount=?, id_categorie=?, image=?, image2=?, image3=?, image4=?, quantité=?, mesuredisponible=?, description=?,disponibilité=? WHERE id=?";
    $sqlState = $pdo->prepare($query);
    $sqlState->execute([$libelle, $prix, $discount, $categorie, $images['image'], $images['image2'], $images['image3'], $images['image4'], $quantité, $mesuredisponible, $description, $disponible,$id]);

    header('location:produits.php');
    exit;
}

// Fetch product details to modify
$id = $_GET['id'];
$sqlState = $pdo->prepare('SELECT * FROM produit WHERE id=?');
$sqlState->execute([$id]);
$produit = $sqlState->fetch(PDO::FETCH_OBJ);
// Sélectionner tous les messages de la table contact
$selectMessagesSql = "SELECT * FROM contact";
$messagesStmt = $pdo->query($selectMessagesSql);
$messages = $messagesStmt->fetchAll(PDO::FETCH_ASSOC);
// Compter le nombre de lignes dans la table contact
$countMessagesSql = "SELECT COUNT(*) as count FROM contact";
$countMessagesStmt = $pdo->query($countMessagesSql);
$countMessages = $countMessagesStmt->fetch(PDO::FETCH_ASSOC);
$messageCount = $countMessages['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Modifier Produit</title>
</head>
<body>
   <!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <img src="../client/images/famillogo.PNG" alt="">
        <!--
        <i class='bx bx-home-alt-2'></i>
        <span class="text">FamiliShop</span>-->
    </a>

    <ul class="side-menu top">
        <li >
            <a  href="catégories.php">
                <i class='bx bx-shopping-bag'></i>
                <span class="text">Liste des Catégories</span>
            </a>
        </li>
        <li class="active">
            <a href="produits.php">
                <i class='bx bx-store'></i>
                <span class="text">Liste des Produits</span>
            </a>
        </li>
        
        <li>
            <a href="commandes.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Commandes</span>
            </a>
        </li>
        <li>
            <a href="commandevalide.php">
            <i class='bx bx-package'></i>                <span class="text">Commandes validées</span>
            </a>
        </li>
        <li>
            <a href="client.php">
            <i class='bx bx-user'></i>
            <span class="text">Clients</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu">
        <li>
            <a href="deconnexion.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Déconnexion</span>
            </a>
        </li>
    </ul>
</section>
<!-- SIDEBAR -->

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification" id="notification-trigger">
    <i class='bx bxs-bell'></i>
    <span class="num"><?php echo $messageCount; ?></span>
</a>
            <section class="messages-section" id="messages-section">
    <?php foreach ($messages as $message) { ?>
        <div class="notifi-item" 
        data-id="<?php echo htmlspecialchars($message['id']); ?>"
             data-nom="<?php echo htmlspecialchars($message['nom']); ?>" 
             data-email="<?php echo htmlspecialchars($message['email']); ?>" 
             data-numtel="<?php echo htmlspecialchars($message['numtel']); ?>" 
             data-message="<?php echo htmlspecialchars($message['message']); ?>">
            <img src="profileimage.webp" alt="img">
            <div class='text'>
                <h4><?php echo htmlspecialchars($message['nom']); ?></h4> 
                <p><?php echo htmlspecialchars($message['message']); ?></p>
            </div>
        </div>
    <?php } ?>
</section>           
        </nav>

        <main class="container">
            <header class="header">Modifier Produit</header>
            <form method="post" class="form" enctype="multipart/form-data">
                <div class="input-box">
                    <input type="hidden" name="id" value="<?= $produit->id ?>">
                    <label>Libellé</label>
                    <input type="text" name="libelle" value="<?= $produit->libelle ?>" required>
                </div>
                <div class="input-box">
                    <label>Description</label>
                    <textarea name="description"><?= $produit->description ?></textarea>
                </div>
                <div class="input-box">
                    <label>Prix</label>
                    <input type="number" name="prix" step="0.01" value="<?= $produit->prix ?>" required>
                </div>
                <div class="input-box">
                    <label>Quantité</label>
                    <input type="number" name="quantité" value="<?= $produit->quantité ?>" required>
                </div>
                <div class="input-box">
                    <label>Mesure disponible</label>
                    <textarea name="mesuredisponible"><?= $produit->mesuredisponible ?></textarea>
                </div>
                <div class="input-box">
                    <label>Discount</label>
                    <input type="number" name="discount" value="<?= $produit->discount ?>" required>
                </div>
                <div class="input-box">
                    <label>Images</label>
                    <?php foreach (['image', 'image2', 'image3', 'image4'] as $field): ?>
                        <div class="input-box">
                            <label for="<?= $field ?>"><?= ucfirst($field) ?></label>
                            <input type="file" name="<?= $field ?>" accept="image/*">
                            <?php if ($produit->$field): ?>
                                <div class="box">
                                    <img src="../upload/produit/<?= $produit->$field ?>" alt="<?= $field ?>" width="100px">
                                </div>
                                <input type="hidden" name="current_<?= $field ?>" value="<?= $produit->$field ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="input-box">
                    <label>Catégorie</label>
                    <select name="categorie" required>
                        <option value="">Choisissez une catégorie</option>
                        <?php
                        $categories = $pdo->query('SELECT * FROM categorie')->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($categories as $cat) {
                            $selected = ($produit->id_categorie == $cat['id']) ? 'selected' : '';
                            echo "<option value='{$cat['id']}' $selected>{$cat['libelle']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="input-box">
                    <button type="submit" name="modifier">Modifier Produit</button>
                </div>
            </form>
        </main>
    </section>
    <script src="script.js"></script>
      <!-- Modale -->
<div class="modals" id="message-modals">
    <h4 id="modals-nom"></h4>
    <p>Email: <span id="modals-email"></span></p>
    <p>Numéro de téléphone: <span id="modals-numtel"></span></p>
    <p>Message: <span id="modals-message"></span></p>
    <form action="suppcontact.php" method="get">
        <input type="hidden" name="id" id="modals-id">
        <button type="submit">Supprimer</button>
        <button type="button" onclick="closeModals()">Fermer</button>
    </form></div>

<!-- Ombre de la page -->
<div class="modals-backdrop" id="modals-backdrop"></div>




<script>
    // Fonction pour ouvrir la modale
    function openModals(id, nom, email, numtel, message) {
        document.getElementById('modals-id').value = id;
        document.getElementById('modals-nom').innerText = nom;
        document.getElementById('modals-email').innerText = email;
        document.getElementById('modals-numtel').innerText = numtel;
        document.getElementById('modals-message').innerText = message;

        document.getElementById('message-modals').style.display = 'block';
        document.getElementById('modals-backdrop').style.display = 'block';

        // Ajouter la classe pour l'ombre
        document.body.classList.add('shadow-overlay');
    }

    // Fonction pour fermer la modale
    function closeModals() {
        document.getElementById('message-modals').style.display = 'none';
        document.getElementById('modals-backdrop').style.display = 'none';

        // Retirer la classe pour l'ombre
        document.body.classList.remove('shadow-overlay');
    }

    // Ajouter des écouteurs d'événements aux éléments de notification
    document.querySelectorAll('.notifi-item').forEach(item => {
        item.addEventListener('click', () => {
            const id = item.getAttribute('data-id');
            const nom = item.getAttribute('data-nom');
            const email = item.getAttribute('data-email');
            const numtel = item.getAttribute('data-numtel');
            const message = item.getAttribute('data-message');

            openModals(id, nom, email, numtel, message);
        });
    });
</script>

<script>
    // Récupérez la référence de la notification et de la section des messages
    const notificationTrigger = document.getElementById('notification-trigger');
    const messagesSection = document.getElementById('messages-section');

    // Ajoutez un gestionnaire d'événement de clic sur la notification
    notificationTrigger.addEventListener('click', function() {
        // Toggle la classe 'show' pour afficher ou cacher la section des messages
        messagesSection.classList.toggle('show');
    });
</script>
</body>
</html>
