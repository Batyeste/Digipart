<?php session_start();
require_once 'enTete.php';

require_once 'variables.inc';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Digipart • Tableau</title>
    <script>
        function confirmDelete(idProduct) {
            var result = confirm("Are you sure you want to delete this product?");
            if (result) {
                window.location.href = "suppCRUD.php?idProduct=" + idProduct;
            } else {
                return false;
            }
        }
    </script>
</head>

<body>
    <div class="container mt-5 ">
        <?php
        if (!empty($_SESSION['erreur'])) {
            echo '<div class="alert alert-danger" role="alert">
                                ' . $_SESSION['erreur'] . '
                            </div>';
            $_SESSION['erreur'] = "";
        }
        if (!empty($_SESSION['message'])) {
            echo '<div class="alert alert-success" role="alert">
                                ' . $_SESSION['message'] . '
                            </div>';
            $_SESSION['message'] = "";
        }
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Description</th>
                    <th>Prix TTC</th>
                    <th>Prix HT</th>
                    <th>Langue</th>
                    <th>Quantité</th>
                    <th>Choix</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "SELECT p.idProduct, p.reference, p.description, p.priceTaxIncl, p.priceTaxExcl, l.langageNom, p.quantite
                            FROM products p
                            INNER JOIN langages l ON p.idLang = l.idLang";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['reference'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['priceTaxIncl'] . "</td>";
                        echo "<td>" . $row['priceTaxExcl'] . "</td>";
                        echo "<td>" . $row['langageNom'] . "</td>";
                        echo "<td>" . $row['quantite'] . "</td>";
                        echo "<td><a href=\"infoCRUD.php?idProduct={$row['idProduct']}\">Voir</a> 
                        <a href=\"editCRUD.php?idProduct={$row['idProduct']}\">Modifier</a> 
                        <a href=\"javascript:void(0);\" onclick=\"confirmDelete(" . $row['idProduct'] . ")\">Supprimer</a>";



                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </tbody>
        </table>
        <a href="ajouter.php" class="btn btn-primary">Ajouter un produit</a>
    </div>
</body>

</html>