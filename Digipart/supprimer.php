<?php session_start();
require_once 'enTete.php';
require_once 'footer.php';
require_once 'variables.inc';
?>

<!DOCTYPE html>
<html>

<head>
  <title>Digipart • Suppression</title>
  <script>
    function confirmDelete() {
      var confirmation = confirm("Êtes-vous sûr de vouloir supprimer les éléments sélectionnés ?");
      if (confirmation) {
        window.location.href = 'supprimerDB.php';
    
        document.forms["deleteForm"].submit();
      }
    }
  </script>
</head>


<body>
<form id="deleteForm" name="deleteForm" action="supprimerDB.php" method="post">
  
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
          <th><input type="submit" id="btnDelete" name="delete" value="Supprimer !" onclick="return confirmDelete()" /></th>
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
            echo "<td><input type='checkbox' name='productsToDelete[]' value='" . $row['idProduct'] . "'></td>";
            echo "</tr>";
          }
        } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
        ?>
       </tbody>
    </table>
    
  </div>
</form>
</body>

</html>