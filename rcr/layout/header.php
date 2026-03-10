<?php
session_start();
// Подключение к базе данных
require_once (__DIR__ . '/../utils/connect/connectToDataBase.php');
$pdo = connectToDataBase();

require_once(__DIR__ . '/../utils/userData.php');

if(isset($_SESSION['user_id'])) {
    // Получаем данные пользователя из базы данных
    $userData = getUserData($_SESSION['user_id'], $pdo);
    $auth = true;
} else {
    $auth = false;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/assets/media/logo/logo_rcr.png">
    <link rel="stylesheet" href="/rcr/assets/global.css">
    <link rel="stylesheet" href="/rcr/lib/bootstrap/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="/rcr/lib/bootstrap/bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <title><?php echo isset($title) ? $title : 'RCR'; ?></title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid container">
        <a class="navbar-brand" href="/index.php"><img src="/rcr/assets/media/logo/logo.png" alt="logo" width="100"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/rcr/index.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/rcr/pages/catalog/index.php">Автопарк</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Контакты</a>
                </li>
            </ul>
            <?php if (!$auth): ?>
                <!-- Если пользователь не авторизован, отобразить кнопку войти -->
                <button type="button" class="buttonAuth" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Войти
                </button>
            <?php else: ?>
                <!-- Если пользователь авторизован, отобразить ссылку на личный кабинет -->
                <a href="/rcr/pages/privateСabinet/index.php" class="buttonAuth authLink"><?php echo $userData['Username']; ?></a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="/rcr/assets/media/logo/logo.png" alt="logo" width="100">
                <form id="loginForm" class="modalForm">
                    <input id="loginInput" class="formInput" type="text" placeholder="Логин" name="username">
                    <input id="passwordInput" class="formInput" type="password" placeholder="Пароль" name="password">
                    <p id="errorMessage" class="errorMessage" style="display: none; color: #404040 !important;"></p>
                    <button id="loginButton" type="button" class="mainButton">Войти</button>
                </form>
                <div class="modalText">
                    <a href="/rcr/pages/registration/index.php" class="modalLink">Регистрация</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('loginButton').addEventListener('click', function() {
        var username = document.getElementById('loginInput').value;
        var password = document.getElementById('passwordInput').value;

        // Отправка данных на сервер для аутентификации
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/rcr/utils/auth.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        window.location.href = '/rcr/pages/privateСabinet/index.php';
                    } else {
                        var errorMessage = document.getElementById('errorMessage');
                        errorMessage.textContent = response.message;
                        errorMessage.style.display = 'block';

                        var loginInput = document.getElementById('loginInput');
                        var passwordInput = document.getElementById('passwordInput');

                        if (response.message.includes('аутентификации') || response.message.includes('логин или пароль')) {
                            loginInput.classList.add('inputError');
                            passwordInput.classList.add('inputError');
                        } else {
                            loginInput.classList.remove('inputError');
                            passwordInput.classList.remove('inputError');
                        }
                    }
                } else {
                    alert('Ошибка ' + xhr.status + ': ' + xhr.statusText);
                }
            }
        };
        xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
    });

    // Скрытие сообщений об ошибке при вводе текста
    document.getElementById('loginInput').addEventListener('input', function() {
        var errorMessage = document.getElementById('errorMessage');
        errorMessage.style.display = 'none';
        this.classList.remove('inputError');
        document.getElementById('passwordInput').classList.remove('inputError');
    });

    document.getElementById('passwordInput').addEventListener('input', function() {
        var errorMessage = document.getElementById('errorMessage');
        errorMessage.style.display = 'none';
        this.classList.remove('inputError');
        document.getElementById('loginInput').classList.remove('inputError');
    });
</script>
</body>
</html>
