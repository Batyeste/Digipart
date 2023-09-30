<?php
session_start();
require_once 'variables.inc';

$message = ""; 

if (
    isset($_POST['reference']) &&    isset($_POST['description']) &&    isset($_POST['priceTaxIncl']) &&    isset($_POST['priceTaxExcl']) &&    isset($_POST['idLang']) &&    isset($_POST['quantite'])
) {
    $reference = $_POST["reference"];
    $description = $_POST["description"];
    $priceTaxIncl = $_POST["priceTaxIncl"];
    $priceTaxExcl = $_POST["priceTaxExcl"];
    $idLang = $_POST["idLang"];
    $quantite = $_POST["quantite"];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE reference = :reference");
        $stmt->bindParam(':reference', $reference);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            $message = "Un produit avec la même référence existe déjà.";
        } else {
            $stmt = $conn->prepare("INSERT INTO products (`reference`, `description`, `priceTaxIncl`, `priceTaxExcl`, `idLang`, `quantite`) 
                VALUES (:reference, :description, :priceTaxIncl, :priceTaxExcl, :idLang, :quantite)");

            $stmt->bindParam(':reference', $reference);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':priceTaxIncl', $priceTaxIncl);
            $stmt->bindParam(':priceTaxExcl', $priceTaxExcl);
            $stmt->bindParam(':idLang', $idLang);
            $stmt->bindParam(':quantite', $quantite);

            $stmt->execute();

            $message = "Insertion effectuée : " . $stmt->rowCount() . " produit(s) ajouté(s).";
            header("Location: index.html");
            exit();
        }
    } catch (PDOException $e) {
        $message = "Echec de l'insertion : " . $e->getMessage();
    }
    $conn = null;
} else {
    $message = "Toutes les données doivent être renseignées.";
    var_dump($_POST);
}

echo "<p>" . $message . "</p>";
?>
