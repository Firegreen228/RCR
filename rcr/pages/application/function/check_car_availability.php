<?php
require_once(__DIR__ . '/../../../utils/connect/connectToDataBase.php');

// Подключаемся к базе данных
$pdo = connectToDataBase();

// Получаем параметры из AJAX запроса
$id_cars = $_GET['id_cars'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

// Проверяем доступность автомобиля на выбранные даты
$query = "SELECT COUNT(*) AS count 
          FROM Lease_contract 
          WHERE Id_car = ? 
            AND ((Start_of_rental <= ? AND End_of_ease >= ?)
                OR (Start_of_rental <= ? AND End_of_ease >= ?)
                OR (Start_of_rental >= ? AND End_of_ease <= ?))";
$statement = $pdo->prepare($query);
$statement->execute([$id_cars, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date]);
$result = $statement->fetch(PDO::FETCH_ASSOC);

// Отправляем результат обратно в JavaScript
echo $result['count'] == 0 ? 'true' : 'false';
?>
