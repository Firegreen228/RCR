<?php
session_start();
require_once('../utils/connect/connectToDataBase.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit();
}

$pdo = connectToDataBase();
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM Reviews WHERE Id_user = ?");
$stmt->execute([$userId]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'reviews' => $reviews]);
?>
