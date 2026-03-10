<?php
require_once (__DIR__ . '/../../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка, была ли отправлена форма методом POST

    // Получаем данные из формы
    $carId = $_POST['carId'];
    $year = $_POST['year'];
    $mileage = $_POST['mileage'];
    $color = $_POST['color'];
    $number = $_POST['number'];
    $price = $_POST['price'];

    // Подготавливаем SQL запрос для обновления данных
    $sql = "UPDATE Cars SET year = :year, mileage = :mileage, color = :color, number = :number, price = :price WHERE id_cars = :carId";

    // Подготавливаем и выполняем запрос
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'year' => $year,
        'mileage' => $mileage,
        'color' => $color,
        'number' => $number,
        'price' => $price,
        'carId' => $carId
    ]);

    header("Location: /pages/index.php");
    exit();
}
?>
