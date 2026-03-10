<?php
require_once(__DIR__ . '/../../../utils/connect/connectToDataBase.php');

$pdo = connectToDataBase();

// Получение данных из POST запроса
$userId = $_POST['user_id'];
$carId = $_POST['id_cars'];
$startOfRental = $_POST['start_date'];
$endOfLease = $_POST['end_date'];
$totalPrice = $_POST['total_price']; // Передаем итоговую стоимость напрямую
$selectedServices = json_decode($_POST['selected_services'], true); // Получаем выбранные дополнительные услуги

// Подготавливаем запрос для создания контракта
$query = "INSERT INTO Lease_contract (Id_user, Id_car, Start_of_rental, End_of_ease, Total_price) VALUES (:Id_user, :Id_car, :Start_of_rental, :End_of_ease, :Total_price)";
$stmt = $pdo->prepare($query);
echo $selectedServices;
// Привязываем параметры для создания контракта
$stmt->bindParam(':Id_user', $userId);
$stmt->bindParam(':Id_car', $carId);
$stmt->bindParam(':Start_of_rental', $startOfRental);
$stmt->bindParam(':End_of_ease', $endOfLease);
$stmt->bindParam(':Total_price', $totalPrice);

// Выполняем запрос для создания контракта
if ($stmt->execute()) {
    // Получаем id созданного контракта
    $contractId = $pdo->lastInsertId();

    // Проверяем, есть ли выбранные дополнительные услуги
    if (!empty($selectedServices)) {
        // Подготавливаем запрос для добавления выбранных дополнительных услуг в Additional_Services
        $queryServices = "INSERT INTO Additional_Services (Id_contract, Id_rates) VALUES (:Id_contract, :Id_rates)";
        $stmtServices = $pdo->prepare($queryServices);

        // Перебираем выбранные услуги и добавляем записи в таблицу Additional_Services
        $stmtServices->bindParam(':Id_contract', $contractId); // Привязываем параметр Id_contract один раз перед началом цикла

        foreach ($selectedServices as $serviceId) {
            // Привязываем параметр Id_rates
            $stmtServices->bindParam(':Id_rates', $serviceId);

            // Выполняем запрос на добавление дополнительной услуги
            if (!$stmtServices->execute()) {
                echo "Ошибка при добавлении дополнительной услуги: " . var_dump($stmtServices->errorInfo());
                exit();
            }
        }
    }

    // Отправляем ответ об успешном создании контракта
    echo json_encode(["message" => "Контракт успешно создан!"]);
    header('Location: /rcr/pages/privateСabinet/index.php');
} else {
    // Выводим сообщение об ошибке при создании контракта
    echo json_encode(["error" => "Ошибка при создании контракта: " . var_dump($stmt->errorInfo())]);
    exit();
}
?>

