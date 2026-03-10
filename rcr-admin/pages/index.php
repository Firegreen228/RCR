<?php
session_start();

// Проверяем, был ли выполнен вход в админ-панель
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Если нет, перенаправляем на страницу входа
    header('Location: /index.php');
    exit();
}

// Подключаемся к базе данных
require_once('../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Обработка выхода из админ-панели
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Удаляем сессию администратора
    session_unset();
    session_destroy();

    // Перенаправляем на страницу входа
    header('Location: /index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css" type="text/css">
    <script src="../utils/js/panel.js"></script>
    <title>Админ-панель</title>
</head>
<body>

<div class="layout">
    <div class="layoutNavBar">
        <div class="layoutLogo">
            <img src="../assets/media/logo_rcr.png" alt="logo" width="50">
        </div>
        <ul class="layoutList">
            <li class="listItem activeLink">
                <a href="#requests" class="itemLink">Заявки</a>
            </li>
            <li class="listItem">
                <a href="#cars" class="itemLink">Автомобили</a>
            </li>
            <li class="listItem">
                <a href="#addCars" class="itemLink">Автопарк</a>
            </li>
            <li class="listItem">
                <a href="#models-brands" class="itemLink">Модели и бренды</a>
            </li>
            <li class="listItem">
                <a href="#reviews" class="itemLink">Отзывы</a>
            </li>
        </ul>
    </div>
    <div class="content">
        <div class="contentHeader">
            <a href="?logout=true" class="logoutButton">Выйти</a>
        </div>

            <!-- Секция по управлению заявками -->
        <section class="contentWrapper" id="requests">
            <div class="contentTitleWrapper">
                <div class="contentMainTitle">Панель управления заявками:</div>
                <div class="contentSubTitle">Модерация и отслеживание заявок</div>
            </div>
                <?php include('section/requests.php')?>
        </section>

            <!-- Секция модерации автопарка -->
        <section class="contentWrapper" id="cars">
            <div class="contentTitleWrapper">
                <div class="contentMainTitle">Панель управления автомобилями:</div>
                <div class="contentSubTitle">Редактирование и отслеживание автомобилей</div>
            </div>
            <?php include('section/cars.php')?>
        </section>

        <section class="contentWrapper" id="addCars">
            <div class="contentTitleWrapper">
                <div class="contentMainTitle">Панель управления автопарком:</div>
                <div class="contentSubTitle">Добавление, удаление автомобилей</div>
            </div>
            <?php include('section/car_park.php')?>
        </section>

        <!-- Секция модерации моделей и брендов -->
        <section class="contentWrapper" id="models-brands">
            <div class="contentTitleWrapper">
                <div class="contentMainTitle">Панель управления брендами и моделями:</div>
                <div class="contentSubTitle">Добавление моделей и брендов</div>
            </div>
            <?php include('section/brands_models.php')?>
        </section>

            <!-- Секция модерации отзывов -->
        <section class="contentWrapper" id="reviews">
            <div class="contentTitleWrapper">
                <div class="contentMainTitle">Панель управления отзывами:</div>
                <div class="contentSubTitle">Удаление отзывов</div>
            </div>
            <?php include('section/reviews.php')?>
        </section>

    </div>
</div>

</body>
</html>
