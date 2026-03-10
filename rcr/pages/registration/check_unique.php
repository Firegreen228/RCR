<?php
require_once('../../utils/connect/connectToDataBase.php');

$pdo = connectToDataBase();


$response = ['status' => 'OK', 'field' => ''];

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE Email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $response['status'] = 'ERROR';
        $response['field'] = 'email';
    }
} elseif (isset($_POST['username'])) {
    $username = $_POST['username'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE Username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $response['status'] = 'ERROR';
        $response['field'] = 'username';
    }
}

echo json_encode($response);
?>
