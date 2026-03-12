<?php
session_start();
require_once '../admin/connexion.php';

if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
  $profileLink = "../profil/_profile.php";
} else {
  $profileLink = "../log/logfami.php";
}

if (isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur'])) {
  $panierLink = "#";
} else {
  $panierLink = "../log/logfami.php";
}

$idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;
$panier = $_SESSION['panier'][$idUtilisateur] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['vider'])) {
        $_SESSION['panier'][$idUtilisateur] = [];
        header('Location: panier.php');
        exit();
    }

    if (isset($_POST['valider'])) {
        $total = 0;
        $prixProduit = [];
        foreach ($panier as $idProduit => $details) {
            $qty = $details['qty'];
            $mesure = $details['mesure'];
            $produit = $pdo->query("SELECT * FROM produit WHERE id = $idProduit")->fetch(PDO::FETCH_ASSOC);
            $prix = $produit['prix'];
            $discount = $produit['discount'];
            $prixd = $prix - (($prix * $discount) / 100);
            $total += $qty * $prixd;
            $prixProduit[$idProduit] = [
                'id' => $idProduit,
                'prix' => $prixd,
                'total' => $qty * $prixd,
                'qty' => $qty,
                'mesure' => $mesure
            ];
        }

        $sqlStateCommande = $pdo->prepare('INSERT INTO commande(id_client, total) VALUES(?, ?)');
        $sqlStateCommande->execute([$idUtilisateur, $total]);
        $idCommande = $pdo->lastInsertId();

        $sql = 'INSERT INTO ligne_commande(id_produit, id_commande, mesure, prix, quantité, total) VALUES ';
        $values = [];
        foreach ($prixProduit as $produit) {
            $values[] = "(:id{$produit['id']}, :idCommande, :mesure{$produit['id']}, :prix{$produit['id']}, :qty{$produit['id']}, :total{$produit['id']})";
        }
        $sql .= implode(', ', $values);

        $sqlState = $pdo->prepare($sql);
        foreach ($prixProduit as $produit) {
            $id = $produit['id'];
            $sqlState->bindParam(":id$id", $produit['id']);
            $sqlState->bindParam(":idCommande", $idCommande);
            $sqlState->bindParam(":mesure$id", $produit['mesure']);
            $sqlState->bindParam(":prix$id", $produit['prix']);
            $sqlState->bindParam(":qty$id", $produit['qty']);
            $sqlState->bindParam(":total$id", $produit['total']);
        }

        $inserted = $sqlState->execute();
        if ($inserted) {
            $_SESSION['panier'][$idUtilisateur] = [];
            $successMessage = "Votre commande avec le montant ({$total} DA) a bien été ajoutée";
        }
    }
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Panier</title>
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
        <?php if ($idUtilisateur): ?>
          <a href="<?php echo $panierLink; ?>"><i class='bx bx-cart-alt'></i> Panier
            <span class="num"><?php echo count($panier); ?></span>
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
    <section id="cart" class="section1">
      <div class="ttre">
        <h2>|</h2>
        <h3>Votre Panier</h3>
      </div>
      <?php
      if (empty($panier)) {
        ?>
        <div class="panier-vide">
          <i class='bx bx-cart-add'></i>
          <p>Remplissez votre Panier</p>
        </div>
        <?php
      } else {
        $total = 0;
        $idProduit = implode(',', array_keys($panier));
        $produits = $pdo->query("SELECT * FROM produit WHERE id IN ($idProduit)")->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table width="100%">
          <thead>
            <tr>
              <td>Supprimer</td>
              <td>Image</td>
              <td>Produit</td>
              <td>Mesure</td>
              <td>Prix</td>
              <td>Quantité</td>
              <td>Total</td>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach($produits as $produit) {
              $quantite = $panier[$produit['id']]['qty'] ?? 0;
              $mesure = $panier[$produit['id']]['mesure'] ?? '/';
              $prix = $produit['prix'];
              $discount = $produit['discount'];
              $prixd = $prix - (($prix * $discount) / 100);
              $subtotal = $prixd * $quantite;
              $total += $subtotal;
              ?>
              <tr>
                <td>
                  <form action="supprimer-panier.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $produit['id']; ?>">
                    <button onclick="return confirm('Voulez-vous vraiment supprimer le produit')" type="submit" name="supprimer"> <i class="far fa-times-circle"></i></button>
                  </form>
                </td>
                <td><img src="../upload/produit/<?php echo $produit['image']; ?>" alt="<?php echo $produit['libelle']; ?>"></td>
                <td><?php echo $produit['libelle']; ?></td>
                <td><?php echo $mesure; ?></td>
                <td><?php echo $prixd; ?> DA</td>
                <td><input type="number" value="<?php echo $quantite; ?>"></td>
                <td><?php echo $subtotal; ?> DA</td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
      </section>
      <section id="cart-add" class="section1">
        <div id="subtotal">
          <table width="100%">
            <thead>
              <tr>
                <h3></h3>
                <td>Prix Totals</td>
                <td></td>
              </tr>
            </thead>
            <tr>
              <td><strong>Total</strong></td>
              <td><strong><?php echo $total; ?> DA</strong></td>
            </tr>
          </table>
          <?php if (isset($successMessage)): ?>
            <div class="success-message" style="color: green; border: 1px solid green; padding: 10px;">
              <?php echo $successMessage; ?>
            </div>
          <?php endif; ?>
          <form method="post">
            <button onclick="return confirm('Voulez-vous vraiment acheter ')"  class="normal" name="valider">Acheter</button>
            <button onclick="return confirm('Voulez-vous vraiment vider le panier')" class="vider" name="vider">Vider le Panier</button>
          </form>
        </div>
      </section>
    </div>
  <?php
  }
  ?>
</body>
</html>







