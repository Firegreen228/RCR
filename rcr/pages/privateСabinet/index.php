<?php
// Подключаем файл для работы с базой данных
require_once('../../utils/connect/connectToDataBase.php');

// Подключаем файл с функцией getUserData
require_once('../../utils/userData.php');

// Проверяем, авторизован ли пользователь
session_start();
if (!isset($_SESSION['user_id'])) {
    // Если пользователь не авторизован, перенаправляем на главную страницу
    header('Location: /index.php');
    exit();
}

// Получаем объект PDO для соединения с базой данных
$pdo = connectToDataBase();

// Получаем данные пользователя из базы данных
$userData = getUserData($_SESSION['user_id'], $pdo);

$title = "Личный кабинет";
include('../../layout/header.php');
?>

<section class="section container">
    <div class="sectionCabinet">
        <div class="cabinetWrapper">
            <div class="cabinetData">
                <img src="../../assets/media/icon/icons8-user-64.png" alt="icon" width="100">
                <div class="cabinetDataWrapper">
                    <div class="cabinetDataUsername"><?php echo htmlspecialchars($userData['Username']); ?></div>
                    <div class="cabinetDataText">
                        <?php
                        echo htmlspecialchars($userData['Surname']) . " " . htmlspecialchars($userData['Name']);
                        if (!empty($userData['Patronymic'])) {
                            echo " " . htmlspecialchars($userData['Patronymic']);
                        }
                        ?>
                    </div>
                    <div class="cabinetDataText">
                        <?php echo htmlspecialchars($userData['Email']); ?>
                    </div>
                </div>
            </div>
            <form action="/rcr/utils/logout.php" method="post">
                <button type="submit" class="mainButton">Выйти</button>
            </form>
        </div>

        <ul class="cabinetMenu">
            <li class="cabinetMenuItem">
                <a href="#application" class="cabinetMenuLink active">Заявки</a>
            </li>
            <li class="cabinetMenuItem">
                <a href="#review" class="cabinetMenuLink">Отзывы</a>
            </li>
            <li class="cabinetMenuItem">
                <a href="#setting" class="cabinetMenuLink">Настройки</a>
            </li>
        </ul>

        <div id="application" class="cabinetContent active">
            <div id="leaseApplications" class="leaseApplications"></div>
        </div>

        <div id="review" class="cabinetContent">
            <div id="userReviews" class="userReviews"></div>
        </div>

        <div id="setting" class="cabinetContent">
            <div class="cabinetDataText" style="margin-bottom: 10px">Редактирование профиля</div>
            <form id="profileForm" class="profileForm" method="post" action="/rcr/utils/updateUser.php">
                <div class="form-group">
                    <input type="text" id="username" name="username" class="regFormInput" value="<?php echo htmlspecialchars($userData['Username']); ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" id="surname" name="surname" class="regFormInput" value="<?php echo htmlspecialchars($userData['Surname']); ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" id="name" name="name" class="regFormInput" value="<?php echo htmlspecialchars($userData['Name']); ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" id="patronymic" name="patronymic" class="regFormInput" value="<?php echo htmlspecialchars($userData['Patronymic']); ?>">
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" class="regFormInput" value="<?php echo htmlspecialchars($userData['Email']); ?>" required>
                </div>
                <button type="submit" class="mainButton">Сохранить изменения</button>
                <div id="updateMessage" style="margin-top: 10px;"></div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const links = document.querySelectorAll('.cabinetMenuLink');
        const contents = document.querySelectorAll('.cabinetContent');
        const reviewContainer = document.getElementById('userReviews');

        links.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                links.forEach(link => link.classList.remove('active'));
                contents.forEach(content => content.classList.remove('active'));
                this.classList.add('active');
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).classList.add('active');
            });
        });

        function getUserReviews() {
            fetch('/rcr/utils/getUserReviews.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.reviews.length > 0) {
                            displayReviews(data.reviews);
                        } else {
                            displayNoReviewsMessage();
                        }
                    } else {
                        console.error('Ошибка получения отзывов:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
        }

        function displayReviews(reviews) {
            reviewContainer.innerHTML = '';
            reviews.forEach(review => {
                const card = document.createElement('div');
                card.classList.add('reviewCard');
                card.innerHTML = `
                    <div class="reviewContent">${review.Text_reviews}</div>
                    <button class="deleteButton mainButton" data-review-id="${review.Id_reviews}">Удалить</button>
                `;
                reviewContainer.appendChild(card);
            });
        }

        function displayNoReviewsMessage() {
            reviewContainer.innerHTML = '<div class="warningMarker">Вы еще не оставили ни одного отзыва</div>';
        }

        getUserReviews();

        reviewContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('deleteButton')) {
                const reviewId = event.target.dataset.reviewId;
                deleteReview(reviewId);
            }
        });

        function deleteReview(reviewId) {
            fetch('/rcr/utils/deleteReview.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reviewId: reviewId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        getUserReviews();
                    } else {
                        console.error('Ошибка удаления отзыва:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const leaseApplicationsContainer = document.getElementById('leaseApplications');

        function getLeaseApplications() {
            fetch('/rcr/utils/getLeaseApplications.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.applications.length > 0) {
                            displayLeaseApplications(data.applications);
                        } else {
                            displayNoApplicationsMessage();
                        }
                    } else {
                        console.error('Ошибка получения заявок:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
        }

        function displayLeaseApplications(applications) {
            leaseApplicationsContainer.innerHTML = '';
            applications.forEach(application => {
                const card = document.createElement('div');
                card.classList.add('leaseApplicationCard');
                card.innerHTML = `
            <div class="applicationContent">
                <a class="applicationContentLink" href="/rcr/pages/catalogItem/index.php?id_cars=${application.id_cars}">${application.Brand} ${application.Car}</a>
                <div class="applicationContentLink">Сроки аренды: ${application.StartDate} - ${application.EndDate}</div>
                <div class="applicationContentLink">Итоговая сумма: ${application.TotalPrice}₽</div>
                ${application.RejectionReason ? `<div class="warningMarker">Бронь отменена: ${application.RejectionReason}</div>` : ''}
            ${application.CanCancel ? `<button class="cancelButton mainButton" data-application-id="${application.Id}">Отменить</button>` : ''}
</div>
        `;
                leaseApplicationsContainer.appendChild(card);
            });
        }

        function displayNoApplicationsMessage() {
            leaseApplicationsContainer.innerHTML = '<div class="warningMarker">У вас нет ни одной заявки на бронирование</div>';
        }

        getLeaseApplications();

        leaseApplicationsContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('cancelButton')) {
                const applicationId = event.target.dataset.applicationId;
                cancelLeaseApplication(applicationId);
            }
        });

        function cancelLeaseApplication(applicationId) {
            fetch('/rcr/utils/cancelLeaseApplication.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ applicationId: applicationId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        getLeaseApplications();
                    } else {
                        console.error('Ошибка отмены заявки:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
        }
    });
</script>


<?php
include('../../layout/footer.php');
?>
