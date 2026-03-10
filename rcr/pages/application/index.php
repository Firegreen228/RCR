<?php
$title = "Бронирование";
include('../../layout/header.php');
require_once('../../utils/connect/connectToDataBase.php');
require_once(__DIR__ . '/../../utils/userData.php');

// Подключаемся к базе данных
$pdo = connectToDataBase();

// Проверяем, передан ли параметр id_cars
if (!isset($_GET['id_cars']) || !is_numeric($_GET['id_cars'])) {
    // Если параметр id_cars не передан или не является числом, выводим сообщение об ошибке
    echo "Ошибка: не передан или некорректный идентификатор машины.";
    exit();
}

// Получаем id машины из параметра запроса
$id_cars = $_GET['id_cars'];

session_start();
$userData = [];
if (isset($_SESSION['user_id'])) {
    $userData = getUserData($_SESSION['user_id'], $pdo);
}

// Получаем цену за день аренды машины
$carPricePerDay = getCarPrice($pdo, $id_cars);

// Передаем цену за день аренды машины в JavaScript
echo '<script>';
echo 'let carPricePerDay = ' . $carPricePerDay . ';';
echo '</script>';
?>

<section class="section container" style="width: auto; display: flex; align-items: center;">
    <form id="bookingForm" class="bookingForm" action="/rcr/pages/application/function/book_car.php" method="post" onsubmit="return validateForm()">

        <!-- Добавляем скрытое поле для передачи информации о выбранных дополнительных услугах -->
        <input type="hidden" id="selected_services" name="selected_services" value="">

        <!-- Скрытое поле для передачи идентификатора пользователя -->
        <input type="hidden" name="user_id" value="<?php echo isset($userData['Id_user']) ? $userData['Id_user'] : ''; ?>">
        <div class="containerFormApp">
            <div class="sectionTitle">Выберите даты аренды</div>
            <div class="calculatorWrapper calculatorWrapperForm">
                <div class="calculatorInputWrapper">
                    <label for="start_date" class="calculatorLabel">Начало:</label>
                    <input type="date" id="start_date" name="start_date" class="calculatorInput" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required onchange="calculateTotalCost()">                </div>
                <div style="color: #ffffffbf;" class="qwe">&minus;</div>
                <div class="calculatorInputWrapper">
                    <label for="end_date" class="calculatorLabel">Окончание:</label>
                    <input type="date" id="end_date" name="end_date" class="calculatorInput" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required onchange="calculateTotalCost()">
                </div>
            </div>

            <input type="hidden" name="id_cars" value="<?php echo $id_cars; ?>">
            <input type="hidden" id="total_price" name="total_price" value="" >

            <!-- Элемент для отображения итоговой стоимости -->
            <p id="total_cost" class="descriptionDataText"></p>
        </div>
        <div class="containerFormApp">
            <div class="sectionTitle">Выберите дополнительные услуги</div>


                <?php
                // Запрос для получения всех дополнительных услуг из таблицы Rates
                $stmt = $pdo->query("SELECT * FROM Rates");

                // Перебираем результаты запроса
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Выводим чекбокс для каждой дополнительной услуги
                    echo '<div class="checkBoxWrapper"><input type="checkbox" class="regFormCheckbox" id="service_' . $row['Id_rates'] . '" name="services[]" value="' . $row['Price_rates'] . '" onchange="calculateTotalCost()">';
                    echo '<label class="checkboxLabel" for="service_' . $row['Id_rates'] . '">' . $row['Name_rates'] . ' (' . $row['Price_rates'] . ' рублей за день)</label></div>';
                }
                ?>

            <!-- Кнопка для бронирования -->
            <button type="submit" class="mainButton cButton">Забронировать</button>
        </div>

    </form>
</section>

<!-- JavaScript код для динамического вычисления и отображения итоговой стоимости -->
<script>
    // Функция для рассчета стоимости аренды
    function calculateTotalCost() {
        let startDate = document.getElementById('start_date').value;
        let endDate = document.getElementById('end_date').value;

        // Рассчитываем разницу в днях между датами
        let start = new Date(startDate);
        let end = new Date(endDate);
        let differenceInTime = end.getTime() - start.getTime();
        let differenceInDays = differenceInTime / (1000 * 3600 * 24);

        // Рассчитываем стоимость аренды без дополнительных услуг
        let totalCost = differenceInDays * carPricePerDay;

        // Получаем выбранные дополнительные услуги
        let selectedServices = document.querySelectorAll('input[name="services[]"]:checked');

        // Перебираем выбранные услуги и добавляем их стоимость к общей стоимости
        selectedServices.forEach(function(service) {
            totalCost += parseFloat(service.value) * differenceInDays;
        });

        // Отображаем итоговую стоимость на странице и устанавливаем значение скрытого поля
        document.getElementById('total_cost').innerHTML = "Итоговая стоимость аренды: " + totalCost.toFixed(2) + " рублей";
        document.getElementById('total_price').value = totalCost.toFixed(2);

        return totalCost; // Возвращаем общую стоимость
    }

    // Функция для бронирования машины
    function bookCar() {
        let startDate = document.getElementById('start_date').value;
        let endDate = document.getElementById('end_date').value;
        let userId = document.querySelector('input[name="user_id"]').value;
        let totalPrice = calculateTotalCost();
        let selectedServices = getSelectedServices(); // Получаем выбранные дополнительные услуги

        let data = {
            start_date: startDate,
            end_date: endDate,
            id_cars: <?php echo $id_cars; ?>,
            user_id: userId,
            total_price: totalPrice,
            selected_services: selectedServices // Передаем выбранные дополнительные услуги
        };

        console.log("Booking Data:", data); // Отладочное сообщение

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/rcr/pages/application/function/book_car.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    alert("Машина успешно забронирована на период с " + startDate + " по " + endDate + ".\n" + response.message);
                } else {
                    alert("Ошибка при бронировании машины: " + xhr.statusText);
                }
            }
        };
        xhr.send(JSON.stringify(data));
    }

    // Функция для получения списка выбранных дополнительных услуг
    function getSelectedServices() {
        let selectedServices = [];
        let checkboxes = document.querySelectorAll('input[name="services[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            selectedServices.push(checkbox.value);
        });

        console.log("Selected Services:", selectedServices); // Отладочное сообщение

        return selectedServices;
    }



    // Функция для валидации формы перед отправкой
    function validateForm() {
        let startDate = document.getElementById('start_date').value;
        let endDate = document.getElementById('end_date').value;

        // Проверяем доступность автомобиля на выбранные даты
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/rcr/pages/application/function/check_car_availability.php?id_cars=<?php echo $id_cars; ?>&start_date=' + startDate + '&end_date=' + endDate, false);
        xhr.send();
        let isAvailable = xhr.responseText === 'true';

        if (!isAvailable) {
            // Если автомобиль недоступен на выбранные даты, выводим сообщение об ошибке
            alert("Ошибка: выбранный автомобиль недоступен на указанные даты.");
            return false; // Отменяем отправку формы
        }

        return true; // Продолжаем отправку формы
    }
</script>

<?php
// Функция для получения цены за день аренды машины
function getCarPrice($pdo, $id_cars) {
    $query = "SELECT price FROM Cars WHERE id_cars = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$id_cars]);
    $car = $statement->fetch(PDO::FETCH_ASSOC);
    return $car['price'];
}
?>
<?php
include('../../layout/footer.php');
?>
