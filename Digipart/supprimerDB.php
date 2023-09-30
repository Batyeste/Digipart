<?php
session_start();
require_once 'enTete.php';
require_once 'footer.php';
require_once 'variables.inc';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"]) && isset($_POST["productsToDelete"])) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $productsToDelete = $_POST["productsToDelete"];

        if (!empty($productsToDelete)) {
            $placeholders = implode(',', array_fill(0, count($productsToDelete), '?'));

            $sql = "DELETE FROM products WHERE idProduct IN ($placeholders)";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute($productsToDelete)) {
                echo "Éléments supprimés avec succès.";
                
                
            } else {
                echo "Erreur lors de la suppression des éléments.";
            }
        } else {
            echo "Aucun élément sélectionné pour la suppression.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Erreur : Les données du formulaire sont incorrectes.";
}
?>
