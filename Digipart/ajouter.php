<?php session_start();
  require_once 'variables.inc';
  require_once 'enTete.php';
?>
<head>
    <title>Digipart • Panel</title>
</head>
<div class="container">
    <form action="ajoutBDD.php" method="post" class="centerform">
        <h1>Ajouter un Produit</h1>
        <div class="mb-3">
            <label for="reference" class="form-label">Référence :</label>
            <input name="reference" required="required" id="reference" type="text" class="form-control" placeholder="Référence du produit">
        </div>
        <div class="mb-3">
        <label for="description" class="form-label">Description :</label>
<textarea name="description" id="description" rows="3" class="form-control" placeholder="Description du produit"></textarea>
 </div>
        <div class="mb-3">
            <label for="priceTaxExcl" class="form-label">Prix HT :</label>
            <input name="priceTaxExcl" required="required" id="priceTaxExcl" type="number" step="any" class="form-control" placeholder="Prix HT">
        </div>
        <div class="mb-3">
            <label for="priceTaxIncl" class="form-label">Prix TTC :</label>
            <input name="priceTaxIncl" required="required" id="priceTaxIncl" type="number" step="any" class="form-control" placeholder="Prix TTC">
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
            <input name="quantite" required="required" id="quantite" type="number" class="form-control" placeholder="Quantité">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Ajouter le Produit</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="calculHT.js"></script>
</body>

<?php require_once 'footer.php'; ?>
