<?php
require_once (__DIR__ . '/../../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Получаем данные из POST-запроса
$requestId = $_POST['request_id'];
$reason = $_POST['reason'];

// Обновляем статус заявки в таблице Lease_contract
$sql = "UPDATE Lease_contract SET Status = 'canceled' WHERE Id = :requestId";
$stmt = $pdo->prepare($sql);
$stmt->execute(['requestId' => $requestId]);

// Добавляем запись об отмене в таблицу Canceled_Applications
$sql = "INSERT INTO Canceled_Applications (Id_contract, Rejection_Reason) VALUES (:requestId, :reason)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['requestId' => $requestId, 'reason' => $reason]);

// Возвращаем успешный статус ответа
http_response_code(200);
?>
