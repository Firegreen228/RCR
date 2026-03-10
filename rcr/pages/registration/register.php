<?php
require '../../utils/connect/connectToDataBase.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../lib/PHPMailer-master/src/PHPMailer.php';
require '../../lib/PHPMailer-master/src/SMTP.php';
require '../../lib/PHPMailer-master/src/Exception.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $patronymic = htmlspecialchars($_POST['patronymic']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка на пустые поля
    if (empty($name) || empty($surname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        die("Заполните все поля.");
    }

    // Проверка совпадения паролей
    if ($password !== $confirm_password) {
        die("Пароли не совпадают.");
    }

    // Хэширование пароля
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Генерация кода подтверждения (6-значный числовой код)
    $confirmation_code = mt_rand(100000, 999999);

    $pdo = connectToDataBase();
    // Проверка на существующего пользователя
    $checkQuery = "SELECT Id_user FROM Users WHERE Email = :email";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        die("Пользователь с такой почтой уже зарегистрирован.");
    }

    // Настройка PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Настройки сервера
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rcrofficial24@gmail.com';
        $mail->Password = 'ujnq sxyx xjfd fepi'; // Замените на пароль от вашей почты
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Установка кодировки
        $mail->CharSet = 'UTF-8';

        // Получатели
        $mail->setFrom('rcrofficial24@gmail.com', 'RCR');
        $mail->addAddress($email);

        // Контент
        $mail->isHTML(true);
        $mail->Subject = 'Подтверждение регистрации';
        $mail->Body    = "Добро пожаловать, $name $surname!<br>Ваш код подтверждения: <b>$confirmation_code</b>";
        $mail->AltBody = "Добро пожаловать, $name $surname!\nВаш код подтверждения: $confirmation_code";

        $mail->send();

        // Сохранение данных в базе данных
        $query = "INSERT INTO Users (Name, Surname, Patronymic, Email, Username, Password, Confirmation_code, isActive) VALUES (:name, :surname, :patronymic, :email, :username, :password, :confirmation_code, false)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'patronymic' => $patronymic,
            'email' => $email,
            'username' => $username,
            'password' => $hashed_password,
            'confirmation_code' => $confirmation_code
        ]);

        // Сохранение email в сессию
        $_SESSION['confirmation_email'] = $email;

        // Перенаправление на страницу подтверждения
        header("Location: /rcr/pages/registration/confirm_registration.php");
        exit();

    } catch (Exception $e) {
        echo "Письмо не может быть отправлено. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Некорректный метод запроса.";
}
?>
