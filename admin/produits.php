<?php
require_once 'connexion.php';

// Vérifiez si une recherche a été effectuée
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    // Protéger contre les injections SQL
    $searchQuery = htmlspecialchars($searchQuery);

    // Requête SQL avec clause LIKE pour la recherche
    $sql = "SELECT produit.*, categorie.libelle as 'categorie_libelle' 
            FROM produit 
            INNER JOIN categorie ON produit.id_categorie = categorie.id 
            WHERE produit.libelle LIKE :search OR produit.description LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search' => '%' . $searchQuery . '%']);
} else {
    // Si aucune recherche, afficher tous les produits
    $stmt = $pdo->query("SELECT produit.*, categorie.libelle as 'categorie_libelle' 
                         FROM produit 
                         INNER JOIN categorie ON produit.id_categorie = categorie.id");
}

$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Sélectionner tous les messages de la table contact

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

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="style.css">

    <title>Liste des Produits</title>
    <style>
        /* Style for the modal background */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5); /* Black background with opacity */
            justify-content: center;
            align-items: center;
        }
        /* Style for the modal content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Can be adjusted */
            max-width: 700px; /* Maximum width */
        }
        /* Style for the image inside the modal */
        .modal-content img {
            width: 100%; /* Make the image responsive */
        }
        /* Style for the close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu' ></i>
            
            <form action="produits.php" method="GET">
                <div class="form-input">
                    <input type="search" name="query" placeholder="Search..." value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
                    <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
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
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Liste des Produits</h3>
                        <a class="plus" href="ajouter-produit.php"><i class='bx bx-plus' ></i></a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Libelle</th>
                                <th>Image</th>
                                <th>Prix</th>
                                <th>Discount</th>
                                <th>Catégorie</th>
                                <th>Disponible</th>
                                <th>Opération</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($produits as $produit) {
                            ?>
                            <tr>
                                <td><?php echo $produit['id']; ?></td>
                                <td><?php echo $produit['libelle']; ?></td>
                                <td><img src="../upload/produit/<?php echo $produit['image']; ?>" width="70px" onclick="displayImage('<?php echo $produit['image']; ?>')"></td>
                                <td><?php echo $produit['prix']; ?>Da</td>
                                <td><?php echo $produit['discount']; ?>%</td>
                                <td><?php echo $produit['categorie_libelle']; ?></td>
                                <td><?php echo ($produit['quantité'] > 0) ? "Oui" : "Non"; ?></td>
                                <td>
                                    <a href="modifierprod.php?id=<?php echo $produit['id']; ?>"><button class="btn2" style="background-color:  #3C91E6;"><i class='bx bx-pencil'></i></button></a>
                                    <a href="supprimprod.php?id=<?php echo $produit['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer le produit <?php echo $produit['libelle']; ?>?')"><button class="btn2" style="background-color: #DB504A;"><i class='bx bx-trash'></i></button></a>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- The Modal -->
    <div id="imageModal" class="modal">
        <!-- The Close Button -->
        <span class="close"><i class='bx bx-x'></i></span>
        <!-- Modal Content (The Image) -->
        <img class="modal-content" id="img01">
        <!-- Modal Caption (Image Text) -->
        <div id="caption"></div>
    </div>

    <!-- Script JavaScript pour la fermeture du modal -->
    <script>
        // Get the <span> element that closes the modal
        var span = document.querySelector("#imageModal .close");
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>
    <!-- Script JavaScript pour l'affichage de l'image -->
    <script>
        function displayImage(imageUrl) {
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("img01");
            modal.style.display = "block";
            modalImg.src = "../upload/produit/" + imageUrl;
            modalImg.style.width = "200px"; // Définir la largeur de l'image
        }
    </script>
    <!-- Script JavaScript pour la fermeture du modal -->
    <script>
        // Fermer le modal lorsque l'utilisateur clique en dehors de l'image
        window.onclick = function(event) {
            var modal = document.getElementById("imageModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        // Get the <span> element that closes the modal
        var span = document.querySelector("#imageModal .close");
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>
    <!-- Your additional JavaScript files -->
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
    </form>
</div>

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
