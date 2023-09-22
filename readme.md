### API Руководство

#### Аутентификация

- **URL:** `/api/token`
- **Метод:** `POST`
- **Параметры:**
    - `login`: Имя пользователя
    - `password`: Пароль
- **Ответ:**
    - Токен для доступа к API

Пример:
```json
{
    "result": {
        "token": "api_token"
    }
}
```

#### Регистрация нового пользователя

- **URL:** `/api/register`
- **Метод:** `POST`
- **Параметры:**
    - `login`: Логин пользователя
    - `first_name`: Имя
    - `last_name`: Фамилия
    - `birth_date`: Дата рождения
    - `password`: Пароль
- **Ответ:**
    - Токен для доступа к API

Пример:
```json
{
    "result": {
        "token": "api_token"
    }
}
```

#### Получение информации о текущем пользователе

- **URL:** `/api/me`
- **Метод:** `GET`
- **Заголовки:**
    - `Authorization`: `Bearer api_token`
- **Ответ:**
    - Информация о текущем пользователе

Пример:
```json
{
    "result": {
        "user": {
            "id": 1,
            "login": "user_login",
            "first_name": "John",
            "last_name": "Doe",
            "birth_date": "1999-01-01"
        }
    }
}
```

#### Создание нового события

- **URL:** `/api/event`
- **Метод:** `POST`
- **Заголовки:**
    - `Authorization`: `Bearer api_token`
- **Параметры:**
    - `title`: Название события
    - `text`: Описание события
- **Ответ:**
    - Информация о созданном событии

Пример:
```json
{
    "result": {
        "event": {
            "title": "Sample Event",
            "text": "Event description"
        }
    }
}
```

#### Получение списка всех событий

- **URL:** `/api/events`
- **Метод:** `GET`
- **Заголовки:**
    - `Authorization`: `Bearer api_token`
- **Ответ:**
    - Список всех событий

Пример:
```json
{
    "result": {
        "events": [
            {
                "id": 1,
                "title": "Event Title",
                "text": "Event Description"
            }
        ]
    }
}
```

#### Присоединение к событию

- **URL:** `/api/event/{event_id}/join`
- **Метод:** `POST`
- **Заголовки:**
    - `Authorization`: `Bearer api_token`

#### Покинуть событие

- **URL:** `/api/event/{event_id}/leave`
- **Метод:** `POST`
- **Заголовки:**
    - `Authorization`: `Bearer api_token`

#### Удаление события

- **URL:** `/api/event/{event_id}`
- **Метод:** `DELETE`
- **Заголовки:**
    - `Authorization`: `Bearer api_token`

---