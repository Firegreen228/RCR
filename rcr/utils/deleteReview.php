<?php
require_once('../utils/connect/connectToDataBase.php');
session_start();

header('Content-Type: application/json'); // Устанавливаем заголовок JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$review_id = $data['reviewId'];

if (!is_numeric($review_id)) {
    echo json_encode(['success' => false, 'message' => 'Неверный идентификатор отзыва.']);
    exit();
}

$pdo = connectToDataBase();

// Проверяем, что отзыв принадлежит текущему пользователю
$stmt = $pdo->prepare("SELECT Id_user FROM Reviews WHERE Id_reviews = :review_id");
$stmt->execute(['review_id' => $review_id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if ($review === false) {
    echo json_encode(['success' => false, 'message' => 'Отзыв не найден.']);
    exit();
}

if ($review['Id_user'] != $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Вы не можете удалить этот отзыв.']);
    exit();
}

// Удаляем отзыв
$stmt = $pdo->prepare("DELETE FROM Reviews WHERE Id_reviews = :review_id");
$stmt->execute(['review_id' => $review_id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Отзыв успешно удален.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при удалении отзыва.']);
}
?>
