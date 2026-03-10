<?php
require_once('../utils/connect/connectToDataBase.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit();
}

$pdo = connectToDataBase();
$userId = $_SESSION['user_id'];
$username = trim($_POST['username']);
$surname = trim($_POST['surname']);
$name = trim($_POST['name']);
$patronymic = trim($_POST['patronymic']);
$email = trim($_POST['email']);

// Проверка уникальности имени пользователя
$stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE Username = ? AND Id_user != ?");
$stmt->execute([$username, $userId]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(['success' => false, 'message' => 'Имя пользователя уже занято']);
    exit();
}

// Обновление данных пользователя
$stmt = $pdo->prepare("UPDATE Users SET Username = ?, Surname = ?, Name = ?, Patronymic = ?, Email = ? WHERE Id_user = ?");
if ($stmt->execute([$username, $surname, $name, $patronymic, $email, $userId])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка обновления данных']);
}
header('Location: /rcr/pages/privateСabinet/index.php');
?>
