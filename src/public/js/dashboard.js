const currentUserId = window.userId;

// Получим токен для запросов на сервер
let CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Будем обновлять просмотр события через 30 секунд
// если пользователь открывает новое обновляем таймер
let eventDetailsInterval;

document.addEventListener('DOMContentLoaded', function () {
    updateEvents();
    setInterval(updateEvents, 30000);
});

// Добавим обработчик для событий всех событий
document.querySelector('#all-events-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('event-link')) {
        e.preventDefault();
        const eventId = e.target.getAttribute('data-event-id');
        getEventDetails(eventId);
    }
});

// Добавим обработчик для событий пользователя
document.querySelector('#user-events-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('event-link')) {
        e.preventDefault();
        const eventId = e.target.getAttribute('data-event-id');
        getEventDetails(eventId);
    }
});


// Форматируем дату в понятный вид
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = {year: 'numeric', month: 'long', day: 'numeric'};
    return date.toLocaleDateString('ru-RU', options);
}

// Выводим список участников события
function formatParticipants(participants) {
    if (participants.length === 0) {
        return '<li class="none-style-li">Нет участников</li>';
    }
    return participants.map(participant => `
        <li data-user-info="<ul><li>Имя: ${participant.first_name} ${participant.last_name}</li><li>Логин: ${participant.login}</li><li>Дата регистрации: ${formatDate(participant.created_at)}</li></ul>">
            <a href="#" class="user-link">${participant.first_name} ${participant.last_name}</a>
        </li>
    `).join('');
}

// Проверяем и показываем кнопку участия в событии или покинуть событие
// participants - участники события
function getParticipationButton(participants, currentUserId, eventId) {
    // Пройдемся по все участникам и узнаем, является ли наш пользователь одним из них
    const isParticipant = participants.some(participant => participant.id === currentUserId);

    if (isParticipant) {
        return `<button class="btn btn-danger leave-event-btn" data-event-id="${eventId}">Покинуть событие</button>`;
    } else {
        return `<button class="btn btn-info join-event-btn" data-event-id="${eventId}">Принять участие</button>`;
    }
}

// Присоединиться к событию
function joinEvent(eventId) {
    fetch(`event/${eventId}/join`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
    })
        .then(response => response.json())
        .then(data => {
            // Обновим экран события и списки событий
            getEventDetails(eventId)
            updateEvents()
        })
        .catch(error => {
            console.error('Ошибка при присоединении к событию:', error);
        });
}

// Покинуть событие
function leaveEvent(eventId) {
    fetch(`event/${eventId}/leave`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
    })
        .then(response => response.json())
        .then(data => {
            // Обновим экран события и списки событий
            getEventDetails(eventId)
            updateEvents()
        })
        .catch(error => {
            console.error('Ошибка при покидании события:', error);
        });
}

// Детальный просмотр события
function getEventDetails(eventId) {
    // Если таймер установлен произведем сброс
    if (eventDetailsInterval) {
        clearInterval(eventDetailsInterval);
    }

    fetch(`event/${eventId}`)
        .then(response => response.json())
        .then(data => {
            const participantsList = formatParticipants(data.result.participants);
            const joinButton = getParticipationButton(data.result.participants, currentUserId, eventId);
            document.querySelector('.col-md-10').innerHTML = `
                <h2>${data.result.title}</h2>
                <p>${data.result.text}</p>
                <p>Дата создания события: ${formatDate(data.result.creation_date)}</p>
                <br>
                <hr>
                <h3>Участники:</h3>
                <ul>${participantsList}</ul>
                ${joinButton}
            `;

            // Добавляем обработчик событий для кнопки "Принять участие"
            const joinEventBtn = document.querySelector('.join-event-btn');
            if (joinEventBtn) {
                joinEventBtn.addEventListener('click', function () {
                    joinEvent(this.getAttribute('data-event-id'));
                });
            }

            // Добавляем обработчик событий для кнопки "Покинуть событие"
            const leaveEventBtn = document.querySelector('.leave-event-btn');
            if (leaveEventBtn) {
                leaveEventBtn.addEventListener('click', function () {
                    leaveEvent(this.getAttribute('data-event-id'));
                });
            }

            // Установим таймер для обновления данных о событие
            // Например список участников
            eventDetailsInterval = setInterval(() => {
                getEventDetails(eventId);
            }, 30000);
        })
        .catch(error => {
            console.error('Ошибка при получении данных события:', error);
        });
}

// обновляем данные о событиях на сервере
function updateEvents() {
    // Запрос для всех событий, где пользователь не участвует
    fetch('/events?filter[not-me]=true')
        .then(response => response.json())
        .then(data => {
            const allEventsList = data.result.map(event => `
                <li class="border-li none-style-li">
                    <a href="#" class="event-link" data-event-id="${event.id}">${event.title}</a>
                </li>
            `).join('');
            document.querySelector('#all-events-list').innerHTML = allEventsList;
        });

    // Запрос для событий, в которых пользователь участвует
    fetch('/events?filter[participating]=true')
        .then(response => response.json())
        .then(data => {
            const userEventsList = data.result.map(event => `
                <li class="border-li none-style-li">
                    <a href="#" class="event-link" data-event-id="${event.id}">${event.title}</a>
                </li>
            `).join('');
            document.querySelector('#user-events-list').innerHTML = userEventsList;
        });
}

// Показ модального окна с информацией о пользователе
document.body.addEventListener('click', function (e) {
    if (e.target.classList.contains('user-link')) {
        e.preventDefault();
        const userInfo = e.target.parentElement.getAttribute('data-user-info');
        document.getElementById('userInfo').innerHTML = userInfo;
        $('#userModal').modal('show');
    }
});
