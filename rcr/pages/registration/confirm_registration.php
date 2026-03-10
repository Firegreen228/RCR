<?php
ob_start();
$title = "Регистрация";
include('../../layout/header.php');
require_once('../../utils/connect/connectToDataBase.php');

session_start();

$error_message = '';

if (!isset($_SESSION['confirmation_email'])) {
    die("Некорректный доступ.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $confirmation_code = htmlspecialchars($_POST['confirmation_code']);
    $email = $_SESSION['confirmation_email'];

    $pdo = connectToDataBase();
    // Проверка кода подтверждения
    $query = "SELECT Id_user, Username FROM Users WHERE Email = :email AND Confirmation_code = :confirmation_code AND isActive = false";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email, 'confirmation_code' => $confirmation_code]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        $user_id = $user['Id_user'];
        $username = $user['Username'];

        // Активация пользователя
        $updateQuery = "UPDATE Users SET isActive = true WHERE Id_user = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute(['id' => $user_id]);

        // Авторизация пользователя
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;

        // Перенаправление в личный кабинет через JS
        echo "<script>
                window.location.href = '/rcr/pages/privateСabinet/index.php';
              </script>";
        exit();
    } else {
        $error_message = "Неверный код подтверждения или аккаунт уже активирован.";
    }
}
?>

<section class="section container" style="display: flex; justify-content: center;">
    <div class="regForm" style="align-items: center !important;">
        <div class="sectionTitle">Подтверждение регистрации</div>
        <?php if ($error_message): ?>
            <p class="errorMessage"><?= $error_message ?></p>
        <?php endif; ?>
        <form method="POST" action="confirm_registration.php" class="registrationForm">
            <input type="text" id="confirmation_code" name="confirmation_code" required class="regFormInput <?= $error_message ? 'inputError' : '' ?>" placeholder="Код подтверждения"><br>
            <button type="submit" class="mainButton regButton">Подтвердить</button>
        </form>
    </div>
</section>

<?php
include('../../layout/footer.php');
ob_end_flush();
?>
