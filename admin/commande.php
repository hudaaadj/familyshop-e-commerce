<?php
require_once 'connexion.php';

$idCommande = $_GET['id'];

// Prepare and execute the query to fetch the specific order details
$sqlState = $pdo->prepare('
SELECT 
    commande.id,
    commande.total,
    commande.date_creation,
    utilisateur.nom as nom,
    utilisateur.prenom,
    utilisateur.numtel,
    utilisateur.adress
FROM 
    commande 
INNER JOIN 
    utilisateur 
ON 
    commande.id_client = utilisateur.id 
WHERE
    commande.id = ?');
$sqlState->execute([$idCommande]); 
$commande = $sqlState->fetch(PDO::FETCH_ASSOC);
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

    <title>Commande | <?=$commande['id']?></title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 250px;
        }
        .modal-content img {
            width: 100%;
        }
        .close {
            color: #kkk;
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
        <li>
            <a href="produits.php">
                <i class='bx bx-store'></i>
                <span class="text">Liste des Produits</span>
            </a>
        </li>
        
        <li class="active">
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
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Commande</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Client</th>
                                <th>Num Tel</th>
                                <th>Adress</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Opération</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlStatePanier = $pdo->prepare('SELECT ligne_commande.*, produit.libelle, produit.image FROM ligne_commande
                                                                 INNER JOIN produit ON ligne_commande.id_produit=produit.id
                                                                 WHERE id_commande=?');
                                $sqlStatePanier->execute([$idCommande]);
                                $lignesCommandes = $sqlStatePanier->fetchAll(PDO::FETCH_OBJ);
                            ?>
                            <tr>
                                <td><?php echo $commande['id'] ?></td>
                                <td><?php echo $commande['nom'] ?> <?php echo $commande['prenom'] ?> </td>
                                <td><?php echo $commande['numtel'] ?></td>
                                <td><?php echo $commande['adress'] ?></td>
                                <td><?php echo $commande['total'] ?></td>
                                <td><?php echo $commande['date_creation'] ?></td>
                                <td>
                                   <button class="btn2" style="background-color: #3C91E6;" onclick="validerCommande(<?php echo $commande['id']; ?>)"> 
                                        <i class='bx bx-check'></i>
                                    </button>
                                    <form action="delete_order.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $commande['id']; ?>">
                                        <button type="submit" class="btn2" style="background-color: red;" onclick="return confirm('Voulez-vous vraiment supprimer cette commande ?');">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="head">
                        <h3>Détails</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Produit</th>
                                <th>Image</th>
                                <th>Mesure</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                                <th>Opération</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($lignesCommandes as $lignesCommande) : ?>
                                <tr>
                                    <td><?php echo $lignesCommande->id ?></td>
                                    <td><?php echo $lignesCommande->libelle ?></td>
                                    <td><img src="../upload/produit/<?php echo $lignesCommande->image ?>" width="70px" onclick="displayImage(this.src)"></td>
                                    <td><?php echo $lignesCommande->mesure ?></td>
                                    <td><?php echo $lignesCommande->prix ?>Da</td>
                                    <td><?php echo $lignesCommande->quantité ?></td>
                                    <td><?php echo $lignesCommande->total ?>Da</td>
                                    <td>
                                        <form action="delete_order_line.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id_ligne_commande" value="<?php echo $lignesCommande->id; ?>">
                                            <button type="submit" class="btn2" style="background-color: red;" onclick="return confirm('Voulez-vous vraiment supprimer cette ligne de commande ?');">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- The Modal -->
    <div id="myModal" class="modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="">
        </div>
    </div>

    <script>
        function displayImage(src) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("modalImage");
            modal.style.display = "flex";
            modalImg.src = src;
        }

        function closeModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}

function validerCommande(idCommande) {
    if (confirm("Voulez-vous vraiment valider cette commande ?")) {
        // Redirection vers commandevalide.php avec l'id de la commande
        window.location.href = "commandevalide.php?id=" + idCommande + "&action=valider";
    }
}
</script>

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
    function openModals(id,nom, email, numtel, message) {
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

            openModals(id,nom, email, numtel, message);
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

           


