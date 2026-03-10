<?php
require_once(__DIR__ . '/../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Проверяем, получен ли параметр brandId
if (isset($_GET['brandId'])) {
    $brandId = $_GET['brandId'];

    // Запрос на получение моделей для выбранного бренда
    $sql = "SELECT model_id, model_name FROM Models WHERE brand_id = :brandId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':brandId', $brandId, PDO::PARAM_INT);
    $stmt->execute();

    // Получаем результаты запроса
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Возвращаем модели в формате JSON
    echo json_encode($models);
} else {
    // Если параметр brandId не был получен, возвращаем пустой массив
    echo json_encode([]);
}
?>
