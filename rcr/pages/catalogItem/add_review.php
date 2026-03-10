<?php
require_once('../../utils/connect/connectToDataBase.php');

// Подключаемся к базе данных
$pdo = connectToDataBase();

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit();
}

// Проверяем, переданы ли необходимые данные из формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cars'], $_POST['reviewText'], $_POST['reviewGrade'])) {
    $id_cars = $_POST['id_cars'];
    $reviewText = $_POST['reviewText'];
    $reviewGrade = $_POST['reviewGrade'];
    $id_user = $_SESSION['user_id'];

    // Вставляем новый отзыв в базу данных
    $query = "INSERT INTO Reviews (Id_car, Id_user, Text_reviews, Grade) VALUES (?, ?, ?, ?)";
    $statement = $pdo->prepare($query);
    $statement->execute([$id_cars, $id_user, $reviewText, $reviewGrade]);

    // Получаем имя пользователя
    $userQuery = "SELECT username FROM Users WHERE Id_user = ?";
    $userStatement = $pdo->prepare($userQuery);
    $userStatement->execute([$id_user]);
    $user = $userStatement->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'username' => $user['username'],
        'reviewText' => $reviewText
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Некорректные данные.']);
}
?>
