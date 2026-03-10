<?php
// Подключаем файл для работы с базой данных
require_once('../utils/connect/connectToDataBase.php');

// Получаем объект PDO для соединения с базой данных
$pdo = connectToDataBase();

// Получаем логин и пароль из запроса
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Проверяем, что логин и пароль не пустые
if (!empty($username) && !empty($password)) {
    // Запрос к базе данных для проверки логина
    $query = "SELECT * FROM Users WHERE Username = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$username]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Если пользователь найден
    if ($user) {
        // Проверяем, активирован ли пользователь
        if (!$user['isActive']) {
            echo json_encode(['success' => false, 'message' => 'Ошибка аутентификации']);
            exit();
        }
        // Проверяем пароль
        if (password_verify($password, $user['Password'])) {
            // Начинаем сессию
            session_start();
            // Записываем идентификатор пользователя в сессию
            $_SESSION['user_id'] = $user['Id_user'];
            // Отправляем успешный ответ
            echo json_encode(['success' => true]);
            exit();
        }
    }
}

// Если аутентификация не удалась, возвращаем ошибку
echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль']);
?>
