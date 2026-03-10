document.addEventListener('DOMContentLoaded', function () {
    // Получаем ссылки на элементы списка и ссылки
    var listItem = document.querySelectorAll('.listItem');
    var itemLink = document.querySelectorAll('.itemLink');

    // Проходимся по каждой ссылке и добавляем обработчик события при клике
    listItem.forEach(function (link, index) {
        link.addEventListener('click', function (event) {
            // Отменяем стандартное поведение ссылки (переход по адресу)
            event.preventDefault();

            // Удаляем класс 'active' у всех ссылок
            listItem.forEach(function (link) {
                link.classList.remove('activeLink');
            });

            // Добавляем класс 'active' к текущей ссылке
            link.classList.add('activeLink');

            // Получаем id секции, к которой ссылка ведет
            var sectionId = itemLink[index].getAttribute('href').substring(1);

            // Показываем только выбранную секцию, скрываем остальные
            var sections = document.querySelectorAll('.contentWrapper');
            sections.forEach(function (section) {
                if (section.id === sectionId) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });

    // Показываем первую секцию при загрузке страницы
    document.getElementById('requests').style.display = 'block';
});
