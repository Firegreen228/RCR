<?php
require_once (__DIR__ . '/../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Ваш PHP-код и SQL-запрос для получения данных о заявках
$sql = "SELECT Users.Name, Users.Surname, Users.Patronymic, Lease_contract.Id, Lease_contract.Start_of_rental, Lease_contract.End_of_ease, Lease_contract.Status, Brands.brand_name, Models.model_name, Cars.number, Canceled_Applications.Rejection_Reason
FROM Lease_contract
JOIN Users ON Lease_contract.Id_user = Users.Id_user
JOIN Cars ON Lease_contract.Id_car = Cars.id_cars
JOIN Models ON Cars.id_models = Models.model_id
JOIN Brands ON Models.brand_id = Brands.id_brand
LEFT JOIN Canceled_Applications ON Lease_contract.ID = Canceled_Applications.Id_contract
";
$stmt = $pdo->query($sql);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
function formatDate($date) {
    // Разбиваем дату на части
    $parts = explode('-', $date);

    // Форматируем дату в нужный вид (дд.мм.гггг)
    return $parts[2] . '.' . $parts[1] . '.' . $parts[0];
}

?>

<div class="filterWrapper">
        <div class="statusFilter">
            <select id="statusFilter" class="statusFilterSelect">
                <option value="all" class="statusFilterItem">Все</option>
                <option value="active" class="statusFilterItem">Активные</option>
                <option value="canceled" class="statusFilterItem">Отмененные</option>
                <option value="completed" class="statusFilterItem">Завершенные</option>
            </select>
        </div>
        <div class="searchWrapper">
            <input type="text" id="idSearch" placeholder="Код заявки" class="searchInput">
        </div>
</div>

<div class="contentRequests">
    <?php foreach ($requests as $request): ?>
        <div class="requestCard">
            <div class="cardHeader">
                <div class="cardStatus <?php echo strtolower($request['Status']); ?>"></div>
                <div class="cardData cardCode"><?php echo $request['Id'] ?></div>
            </div>
            <div class="cardTextWrapper">
                <div class="cardText">фио:</div>
                <div class="cardData">
                    <?php echo $request['Name'] . ' ' . $request['Surname'] . ' ' . $request['Patronymic']; ?>
                </div>
            </div>
            <div class="cardData">
                <?php echo formatDate($request['Start_of_rental']); ?> - <?php echo formatDate($request['End_of_ease']); ?>
            </div>
            <div class="cardText cardTextCenter">Автомобиль:</div>
            <div class="cardTextWrapper">
                <div class="cardText">номер:</div>
                <div class="cardData"><?php echo $request['number'];?></div>
            </div>
            <div class="cardTextWrapper">
                <div class="cardText">модель:</div>
                <div class="cardData"><?php echo $request['brand_name'] . ' ' . $request['model_name']?></div>
            </div>
            <?php if(strtolower($request['Status']) === 'canceled'): ?>
                <div class="cardTextWrapper rejectionReason">
                    <div class="cardText">причина отмены:</div>
                    <div class="cardData">
                        <?php echo $request['Rejection_Reason'];?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(strtolower($request['Status']) === 'active'): ?>
                <div class="cardButtonWrapper">
                    <button class="cardButton" onclick="showCancellationReasonInput(<?php echo $request['Id'] ?>)">Отменить</button>
                </div>
                <div id="cancellationInput<?php echo $request['Id'] ?>" style="display: none;">
                    <div class="cancellationReason">
                        <input type="text" id="reasonInput<?php echo $request['Id'] ?>" placeholder="Введите причину отмены" class="cardInput">
                        <div class="cancellationButtonWrapper">
                            <button onclick="cancelRequest(<?php echo $request['Id'] ?>)" class="cancellationButton">
                                Подтвердить
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var statusFilter = document.getElementById('statusFilter');
        var idSearchInput = document.getElementById('idSearch');
        var requestCards = document.querySelectorAll('.requestCard');

        statusFilter.addEventListener('change', filterRequests);
        idSearchInput.addEventListener('input', searchById);

        function filterRequests() {
            var selectedStatus = statusFilter.value.toLowerCase(); // Преобразуем выбранный статус к нижнему регистру
            requestCards.forEach(function (card) {
                var cardStatus = card.querySelector('.cardStatus').classList[1]; // Получаем класс статуса карточки
                if (selectedStatus === 'all' || cardStatus === selectedStatus) { // Сравниваем значения статусов
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function searchById() {
            var searchId = idSearchInput.value.trim().toLowerCase();
            requestCards.forEach(function (card) {
                var cardId = card.querySelector('.cardCode').textContent.trim().toLowerCase();
                if (cardId.includes(searchId)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    });
    function showCancellationReasonInput(requestId) {
        var cancellationInput = document.getElementById('cancellationInput' + requestId);
        if (cancellationInput) {
            cancellationInput.style.display = 'block';
        }
    }

    function cancelRequest(requestId) {
        var reasonInput = document.getElementById('reasonInput' + requestId).value;

        // Проверяем, заполнено ли поле причины отмены
        if (reasonInput.trim() === '') {
            alert('Пожалуйста, укажите причину отмены.');
            return; // Прерываем выполнение функции, если поле не заполнено
        }

        // Отправка AJAX-запроса для обновления статуса заявки и добавления записи об отмене в базу данных
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Обновление страницы после успешного выполнения запроса
                    window.location.reload();
                } else {
                    // Обработка ошибки, если запрос не выполнен успешно
                    console.error('Ошибка при отмене заявки:', xhr.status);
                }
            }
        };
        xhr.open('POST', '/pages/section/function/cancel_request.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('request_id=' + requestId + '&reason=' + reasonInput);
    }
</script>

