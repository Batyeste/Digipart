<?php
require_once("variables.inc");

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = filter_input_array(INPUT_POST);

    if ($input['action'] == 'edit') {
        $update_field = '';
        $values = array();

        if (isset($input['reference'])) {
            $update_field .= "reference=:reference";
            $values[':reference'] = $input['reference'];
        } else if (isset($input['description'])) {
            $update_field .= "description=:description";
            $values[':description'] = $input['description'];
        } else if (isset($input['priceTaxIncl'])) {
            $update_field .= "priceTaxIncl=:priceTaxIncl";
            $values[':priceTaxIncl'] = $input['priceTaxIncl'];
        } else if (isset($input['priceTaxExcl'])) {
            $update_field .= "priceTaxExcl=:priceTaxExcl";
            $values[':priceTaxExcl'] = $input['priceTaxExcl'];
        } else if (isset($input['idLang'])) {
            $update_field .= "idLang=:idLang";
            $values[':idLang'] = $input['idLang'];
        } else if (isset($input['quantite'])) {
            $update_field .= "quantite=:quantite";
            $values[':quantite'] = $input['quantite'];
        }

        if ($update_field && $input['idProduct']) {
            $sql_query = "UPDATE products SET $update_field WHERE idProduct=:idProduct";
            $values[':idProduct'] = $input['idProduct'];

            $stmt = $conn->prepare($sql_query);
            $stmt->execute($values);
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
