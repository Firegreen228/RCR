<?php
$title = "Регистрация";
include('../../layout/header.php');
require_once('../../utils/connect/connectToDataBase.php');
?>

    <section class="section container registrationSection">
        <div class="regForm">
            <div>
                <div class="sectionTitle">Регистрация</div>
                <div class="sectionSubTitle">Уже есть аккаунт? <span class="buttonAuth color-gold regButtonText" data-bs-toggle="modal" data-bs-target="#exampleModal">Войти</span></div>
            </div>
            <form id="registrationForm" action="register.php" method="POST" class="registrationForm">
                <div class="registrationFormContainer">
                    <div class="registrationFormContainerInput">
                        <input type="text" id="name" name="name" placeholder="Имя" class="regFormInput" required>
                        <div id="name_error" class="errorMessage" style="display: none;">Имя введено некорректно.</div>

                        <input type="text" id="surname" name="surname" placeholder="Фамилия" class="regFormInput" required>
                        <div id="surname_error" class="errorMessage" style="display: none;">Фамилия введена некорректно.</div>

                        <input type="text" id="patronymic" name="patronymic" placeholder="Отчество" class="regFormInput">
                        <div id="patronymic_error" class="errorMessage" style="display: none;">Отчество введено некорректно.</div>

                        <input type="email" id="email" name="email" placeholder="Email" class="regFormInput" required>
                        <div id="email_error" class="errorMessage" style="display: none;">Пользователь с данной почтой уже зарегестрирован.</div>
                    </div>

                    <div class="registrationFormContainerInput">
                        <input type="text" id="username" name="username" placeholder="Придумайте логин" class="regFormInput" required>
                        <div id="username_taken_error" class="errorMessage" style="display: none;">Это имя пользователя уже занято.</div>
                        <div id="username_error" class="errorMessage" style="display: none;">Имя пользователя может содержать только латиницу, цифры или символ "_".</div>

                        <input type="password" id="password" name="password" placeholder="Придумайте пароль" class="regFormInput" required>

                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Повторите пароль" class="regFormInput" required>
                        <div id="password_error" class="errorMessage" style="display: none;">Пароли должны совпадать.</div>

                        <!-- Невидимое поле для защиты от спама -->
                        <div style="display:none;">
                            <label for="honeypot">Заполните это поле:</label>
                            <input type="text" id="honeypot" name="honeypot">
                        </div>

                        <div class="checkBoxWrapper">
                            <input type="checkbox" id="terms" name="terms" class="regFormCheckbox" required>
                            <label for="terms" class="checkboxLabel">
                                Я ознакомился(-лась) и принимаю <a href="#" target="_blank" class="checkboxLink">Условия использования</a> и <a href="#" target="_blank" class="checkboxLink">Политику конфиденциальности</a>.
                            </label><br>
                            <p id="terms_error" class="errorMessage" style="display: none;">Необходимо принять Условия использования и Политику конфиденциальности!</p>
                        </div>
                    </div>
                </div>
                <div class="regButtonWrapper">
                    <input type="submit" value="Зарегистрироваться" class="mainButton regButton">
                </div>
            </form>
        </div>
    </section>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var passwordError = document.getElementById('password_error');
            var confirmPasswordField = document.getElementById('confirm_password');
            var termsChecked = document.getElementById('terms').checked;
            var termsError = document.getElementById('terms_error');
            var valid = true;

            // Проверка паролей при отправке формы
            if (password !== confirmPassword) {
                passwordError.style.display = 'block';
                confirmPasswordField.classList.add('inputError');
                valid = false;
            } else {
                passwordError.style.display = 'none';
                confirmPasswordField.classList.remove('inputError');
            }

            // Проверка чекбокса правил пользования
            if (!termsChecked) {
                termsError.style.display = 'block';
                valid = false;
            } else {
                termsError.style.display = 'none';
            }

            // Проверка валидности имени, фамилии, отчества и имени пользователя
            if (!validateForm()) {
                valid = false;
            }

            if (!valid) {
                event.preventDefault(); // Остановить отправку формы
            }
        });

        function validateForm() {
            var name = document.getElementById('name').value;
            var surname = document.getElementById('surname').value;
            var patronymic = document.getElementById('patronymic').value;
            var username = document.getElementById('username').value;

            var cyrillicPattern = /^[А-ЯЁа-яё\s\-]+$/;
            var usernamePattern = /^[A-Za-z0-9_]+$/;

            var valid = true;

            // Проверка имени
            if (!cyrillicPattern.test(name)) {
                document.getElementById('name_error').style.display = 'block';
                document.getElementById('name').classList.add('inputError');
                valid = false;
            } else {
                document.getElementById('name_error').style.display = 'none';
                document.getElementById('name').classList.remove('inputError');
            }

            // Проверка фамилии
            if (!cyrillicPattern.test(surname)) {
                document.getElementById('surname_error').style.display = 'block';
                document.getElementById('surname').classList.add('inputError');
                valid = false;
            } else {
                document.getElementById('surname_error').style.display = 'none';
                document.getElementById('surname').classList.remove('inputError');
            }

            // Проверка отчества (если введено)
            if (patronymic && !cyrillicPattern.test(patronymic)) {
                document.getElementById('patronymic_error').style.display = 'block';
                document.getElementById('patronymic').classList.add('inputError');
                valid = false;
            } else {
                document.getElementById('patronymic_error').style.display = 'none';
                document.getElementById('patronymic').classList.remove('inputError');
            }

            // Проверка имени пользователя
            if (!usernamePattern.test(username)) {
                document.getElementById('username_error').style.display = 'block';
                document.getElementById('username').classList.add('inputError');
                valid = false;
            } else {
                document.getElementById('username_error').style.display = 'none';
                document.getElementById('username').classList.remove('inputError');
            }

            return valid;
        }

        // Убираем сообщения об ошибках и красный бордер при вводе в поля имени, фамилии, отчества и имени пользователя
        ['name', 'surname', 'patronymic', 'username'].forEach(function(fieldId) {
            document.getElementById(fieldId).addEventListener('input', function() {
                var errorId = fieldId + '_error';
                document.getElementById(errorId).style.display = 'none';
                this.classList.remove('inputError');
            });
        });

        function checkUnique(field, value) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_unique.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'ERROR') {
                        if (response.field === 'email') {
                            document.getElementById('email_error').style.display = 'block';
                            document.getElementById('email').classList.add('inputError');
                        } else if (response.field === 'username') {
                            document.getElementById('username_taken_error').style.display = 'block';
                            document.getElementById('username').classList.add('inputError');
                        }
                    } else {
                        if (field === 'email') {
                            document.getElementById('email_error').style.display = 'none';
                            document.getElementById('email').classList.remove('inputError');
                        } else if (field === 'username') {
                            document.getElementById('username_taken_error').style.display = 'none';
                            document.getElementById('username').classList.remove('inputError');
                        }
                    }
                }
            };
            xhr.send(field + '=' + encodeURIComponent(value));
        }

        // Add event listeners for real-time validation
        document.getElementById('email').addEventListener('input', function() {
            var email = this.value;
            if (email) {
                checkUnique('email', email);
            }
        });

        document.getElementById('username').addEventListener('input', function() {
            var username = this.value;
            if (username) {
                checkUnique('username', username);
            }
        });
    </script>

<?php
include('../../layout/footer.php');
?>