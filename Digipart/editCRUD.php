<?php
session_start();
require_once 'enTete.php';
require_once 'footer.php';
require_once 'variables.inc';

if ($_POST) {

    if (
        isset($_POST['idProduct']) && !empty($_POST['idProduct'])
        && isset($_POST['reference']) && !empty($_POST['reference'])
        && isset($_POST['description']) && !empty($_POST['description'])
        && isset($_POST['priceTaxIncl']) && !empty($_POST['priceTaxIncl'])
        && isset($_POST['priceTaxExcl']) && !empty($_POST['priceTaxExcl'])
        && isset($_POST['idLang']) && !empty($_POST['idLang'])
        && isset($_POST['quantite']) && !empty($_POST['quantite'])
    ) {
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $idProduct = strip_tags($_POST['idProduct']);
            $reference = strip_tags($_POST['reference']);
            $description = strip_tags($_POST['description']);
            $priceTaxIncl = strip_tags($_POST['priceTaxIncl']);
            $priceTaxExcl = strip_tags($_POST['priceTaxExcl']);
            $idLang = strip_tags($_POST['idLang']);
            $quantite = strip_tags($_POST['quantite']);

            $sql = "UPDATE products SET `reference`=:reference, `description`=:description, `priceTaxIncl`=:priceTaxIncl, `priceTaxExcl`=:priceTaxExcl, `idLang`=:idLang, `quantite`=:quantite WHERE idProduct=:idProduct";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':idProduct', $idProduct, PDO::PARAM_INT);
            $stmt->bindValue(':reference', $reference, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':priceTaxIncl', $priceTaxIncl, PDO::PARAM_STR);
            $stmt->bindValue(':priceTaxExcl', $priceTaxExcl, PDO::PARAM_STR);
            $stmt->bindValue(':idLang', $idLang, PDO::PARAM_STR);
            $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['message'] = "Produit modifié";
            $conn = null;
            header('Location: crud.php');
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
        }
    } else {
        var_dump($_POST);
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

// Nouvelle section pour récupérer les données du produit
if (isset($_GET['idProduct']) && !empty($_GET['idProduct'])) {
    try {
        $idProducts = strip_tags($_GET['idProduct']);

        $stmt = $conn->prepare("SELECT p.idProduct, p.reference, p.description, p.priceTaxIncl, p.priceTaxExcl, l.langageNom, p.quantite
        FROM products p
        INNER JOIN langages l ON p.idLang = l.idLang
        WHERE p.idProduct = :idProduct");

        $stmt->bindValue(':idProduct', $idProducts, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            $_SESSION['erreur'] = "Ce produit n'existe pas";
            header('Location: crud.php');
            exit;
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
} else {
    $_SESSION['erreur'] = "URL invalide";
    header('Location: crud.php');
    exit;
}
?>

<head>
    <title>Digipart - Modifier SCRUD</title>
</head>

<body>
    <div class="container">
        <section class="col-12">
            <?php
            if (!empty($_SESSION['erreur'])) {
                echo '<div class="alert alert-danger" role="alert">
                                ' . $_SESSION['erreur'] . '
                            </div>';
                $_SESSION['erreur'] = "";
            }
            ?>
            <h1>Modifier un Produit</h1>
            <form method="post">
                <div class="mb-3">
                    <label for="reference" class="form-label">Référence :</label>
                    <input name="reference" required="required" id="reference" type="text" class="form-control" placeholder="Référence du produit" value="<?= isset($row['reference']) ? $row['reference'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description :</label>
                    <textarea name="description" required="required" id="description" rows="3" class="form-control" placeholder="Description du produit"><?= isset($row['description']) ? $row['description'] : '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="priceTaxExcl" class="form-label">Prix HT :</label>
                    <input name="priceTaxExcl" required="required" id="priceTaxExcl" type="number" step="any" class="form-control" placeholder="Prix HT" value="<?= isset($row['priceTaxExcl']) ? $row['priceTaxExcl'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="priceTaxIncl" class="form-label">Prix TTC :</label>
                    <input name="priceTaxIncl" required="required" id="priceTaxIncl" type="number" step="any" class="form-control" placeholder="Prix TTC" value="<?= isset($row['priceTaxIncl']) ? $row['priceTaxIncl'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="idLang" class="form-label">Langue :</label>
                    <select name="idLang" required="required" id="idLang" class="form-select">
                        <option value="">Sélectionnez une langue</option>
                        <?php
                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $stmt = $conn->prepare("SELECT idLang, langageNom FROM Langages");
                            $stmt->execute();
                            $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($languages as $language) {
                                echo "<option value=\"" . $language['idLang'] . "\">" . $language['langageNom'] . "</option>";
                            }
                        } catch (PDOException $e) {
                            echo "<option value=\"\">Erreur de chargement des langues</option>";
                        }
                        $conn = null;
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantite" class="form-label">Quantité :</label>
                    <input name="quantite" required="required" id="quantite" type="number" class="form-control" placeholder="Quantité" value="<?= isset($row['quantite']) ? $row['quantite'] : '' ?>">
                </div>
                <input type="hidden" value="<?= $row['idProduct'] ? $row['idProduct'] : '' ?>" name="idProduct">
                <button class="btn btn-primary">Envoyer</button>
            </form>
        </section>
    </div>

</body>
<script type="text/javascript" src="calculHT.js"> </script>

</html>