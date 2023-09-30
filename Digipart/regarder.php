<?php session_start();
require_once 'enTete.php';
require_once 'footer.php';
require_once 'variables.inc';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Digipart • Tableau</title>
</head>

<body>
    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Description</th>
                    <th>Prix TTC</th>
                    <th>Prix HT</th>
                    <th>Langue</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "SELECT p.reference, p.description, p.priceTaxIncl, p.priceTaxExcl, l.langageNom, p.quantite
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
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>