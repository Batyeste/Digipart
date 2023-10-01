<?php session_start();
require_once 'enTete.php';
require_once 'footer.php';
require_once 'variables.inc';
?>

<!DOCTYPE html>

<head>
    <title>Digipart - Info CRUD</title>
</head>

<html>

<body>
    <?php
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_GET['idProduct']) && !empty($_GET['idProduct'])) {
            $idProduct = strip_tags($_GET['idProduct']);

            $stmt = $conn->prepare("SELECT p.idProduct, p.reference, p.description, p.priceTaxIncl, p.priceTaxExcl, l.langageNom, p.quantite
            FROM products p
            INNER JOIN langages l ON p.idLang = l.idLang
            WHERE p.idProduct = :idProduct");

            $stmt->bindValue(':idProduct', $idProduct, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(); 

            if (!$row) {
                $_SESSION['erreur'] = "Ce produit n'existe pas";
                header('Location: index.php');
                exit; 
            }
        } else {
            $_SESSION['erreur'] = "URL invalide";
            header('Location: crud.php'); 
            exit; 
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
    ?>

    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php if (isset($row) && $row) : ?>
                    <h1>Référence : <?= $row['reference'] ?> </h1>
                    <p>Description : <?= $row['description'] ?> </p>
                    <p>Prix TTC : <?= $row['priceTaxIncl'] ?> €  </p>
                    <p>Prix HT : <?= $row['priceTaxExcl'] ?> € </p>
                    <p>Langue : <?= $row['langageNom'] ?></p>
                    <p>Quantité  : <?= $row['quantite'] ?> </p>
                    <p><a href="crud.php">Retour</a> 
                    <a href="editCRUD.php?idProduct=<?= $row['idProduct'] ?>">Modifier</a></p>
                <?php else : ?>
                    <p>Élément non trouvé.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

</body>

</html>