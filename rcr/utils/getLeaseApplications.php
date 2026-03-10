<?php
require_once('../utils/connect/connectToDataBase.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit();
}

$pdo = connectToDataBase();
$userId = $_SESSION['user_id'];

$query = "SELECT lc.Id AS Id, b.brand_name AS Brand, m.model_name AS Car, lc.Start_of_rental AS StartDate, lc.End_of_ease AS EndDate, lc.Total_price AS TotalPrice, c.id_cars AS id_cars, ca.Rejection_Reason AS RejectionReason
          FROM Lease_contract lc 
          JOIN Cars c ON lc.Id_car = c.id_cars 
          JOIN Models m ON c.id_models = m.model_id
          JOIN Brands b ON m.brand_id = b.id_brand
          LEFT JOIN Canceled_Applications ca ON lc.Id = ca.Id_contract
          WHERE lc.Id_user = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentDate = new DateTime();

foreach ($applications as &$application) {
    $startDate = new DateTime($application['StartDate']);
    $interval = $currentDate->diff($startDate);
    $canCancel = $interval->invert == 0 && $interval->days >= 1; // invert == 0 означает, что $startDate больше $currentDate
    $application['CanCancel'] = $canCancel;
    $application['RejectionReason'] = $application['RejectionReason'] ?? null; // Добавляем причину отмены если она есть
}


if ($applications) {
    echo json_encode(['success' => true, 'applications' => $applications]);
} else {
    echo json_encode(['success' => true, 'applications' => []]);
}
?>
