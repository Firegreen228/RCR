<?php
session_start();

// Проверяем, был ли выполнен вход в админ-панель
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    // Если да, перенаправляем на страницу админ-панели
    header('Location: /pages/index.php');
    exit();
}

// Подключаемся к базе данных
require_once('utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Проверяем, были ли отправлены данные из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, совпадают ли введенные логин и пароль с ожидаемыми значениями
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'rcradmin') {
        // Логин и пароль верные, устанавливаем сессию администратора
        $_SESSION['admin'] = true;

        // Перенаправляем на страницу админ-панели
        header('Location: /pages/index.php');
        exit();
    } else {
        // Логин или пароль неверные, выводим сообщение об ошибке
        $error_message = "Ошибка: неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
</head>
<body>
<h1>Панель администратора</h1>
<?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Логин:</label>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required><br><br>
    <button type="submit">Войти</button>
</form>
</body>
</html>
