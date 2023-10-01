<?php session_start();
require_once 'enTete.php';
require_once 'footer.php';
require_once 'variables.inc';


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
            header('Location: crud.php');
            exit; 
        }

        $stmt = $conn->prepare("DELETE FROM products WHERE idProduct = :idProduct");
        $stmt->bindValue(':idProduct', $idProduct, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(); 
        header('Location: crud.php');
    } else {
        $_SESSION['erreur'] = "URL invalide";
        header('Location: crud.php'); 
        exit; 
    }
} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
}
