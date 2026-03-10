<?php
require_once (__DIR__ . '/../../../utils/connectToDataBase.php');

// Проверяем, был ли отправлен идентификатор автомобиля для удаления
if (isset($_POST['carId'])) {
    $carId = $_POST['carId'];

    // Подключаемся к базе данных
    $pdo = connectToDataBase();

    try {
        // Начало транзакции
        $pdo->beginTransaction();

        // Подготавливаем SQL запрос для удаления броней автомобиля
        $sqlDeleteLeases = "DELETE FROM Lease_contract WHERE Id_car = ?";
        $stmtDeleteLeases = $pdo->prepare($sqlDeleteLeases);
        $stmtDeleteLeases->execute([$carId]);

        // Подготавливаем SQL запрос для удаления автомобиля
        $sqlDeleteCar = "DELETE FROM Cars WHERE id_cars = ?";
        $stmtDeleteCar = $pdo->prepare($sqlDeleteCar);
        $stmtDeleteCar->execute([$carId]);

        // Завершаем транзакцию
        $pdo->commit();

        // Возвращаем успешный статус ответа
        http_response_code(200);
    } catch (PDOException $e) {
        // В случае ошибки откатываем транзакцию и возвращаем статус 500 и сообщение об ошибке
        $pdo->rollBack();
        http_response_code(500);
        echo "Ошибка при удалении автомобиля: " . $e->getMessage();
    }
} else {
    // Если идентификатор автомобиля не был отправлен, возвращаем статус 400 (Bad Request)
    http_response_code(400);
    echo "Идентификатор автомобиля не был отправлен.";
}
?>
