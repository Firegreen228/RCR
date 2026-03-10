<?php
// Подключаем файл для работы с базой данных
require_once('../utils/connect/connectToDataBase.php');

// Получаем объект PDO для соединения с базой данных
$pdo = connectToDataBase();

// Получаем ID заявки на бронирование, которую нужно отменить
$data = json_decode(file_get_contents("php://input"), true);
$applicationId = $data['applicationId'];

// Подготовленный запрос для удаления заявки на бронирование
$stmt = $pdo->prepare("DELETE FROM Lease_contract WHERE Id = :applicationId");
$stmt->execute(['applicationId' => $applicationId]);

$response = ['success' => true];
echo json_encode($response);
?>
