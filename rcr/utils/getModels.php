<?php
require_once('../utils/connect/connectToDataBase.php');

$pdo = connectToDataBase();

if (isset($_GET['brand']) && !empty($_GET['brand'])) {
    $brand = $_GET['brand'];

    $query = "SELECT Models.model_id, Models.model_name
              FROM Models
              JOIN Brands ON Models.brand_id = Brands.id_brand
              WHERE Brands.brand_name = :brand";

    $statement = $pdo->prepare($query);
    $statement->bindValue(':brand', $brand);
    $statement->execute();
    $models = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($models);
}
?>
