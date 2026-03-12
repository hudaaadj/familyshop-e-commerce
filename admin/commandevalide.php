<?php
require_once 'connexion.php';

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] === 'valider') {
    $idCommande = $_GET['id'];
    try {
        // Mise à jour de l'état de la commande
        $stmt = $pdo->prepare('UPDATE commande SET etat = 1 WHERE id = ?');
        $stmt->execute([$idCommande]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Requête pour récupérer les commandes validées
try {
    $commandesValidees = $pdo->query('
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
            commande.etat = 1
        ORDER BY 
            commande.date_creation DESC
    ')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Query failed: ' . $e->getMessage();
}
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

    <title>Commandes</title>
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
        
        <li>
            <a href="commandes.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Commandes</span>
            </a>
        </li>
        <li class="active">
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
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
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
                        <h3>Commandes valider</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Client</th>
                                <th>Num Tel</th>
                                <th>Adresse</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Opération</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandesValidees as $commande) { ?>
                                <tr>
                                    <td><?php echo $commande['id']; ?></td>
                                    <td><?php echo $commande['nom'] . ' ' . $commande['prenom']; ?></td>
                                    <td><?php echo $commande['numtel']; ?></td>
                                    <td><?php echo $commande['adress']; ?></td>
                                    <td><?php echo $commande['total']; ?></td>
                                    <td><?php echo $commande['date_creation']; ?></td>
                                    <td>
                                        <a href="commande.php?id=<?php echo $commande['id']; ?>">
                                            <button class="btn" style="background-color: #3C91E6;">Voir</button>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Your script inclusion here -->
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
