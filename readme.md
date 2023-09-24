### Установка

### Шаги по установке проекта:

1. **Клонирование репозитория:**

2. **Создание Docker образов и контейнеров:**
   ```bash
   docker-compose up -d
   ```

3. **Установка зависимостей проекта:**
   Для этого нужно зайти в PHP контейнер:
   ```bash
   docker exec -it swc_php bash
   ```
   Затем выполнить команду Composer:
   ```bash
   composer install
   ```

4. **Копирование .env файла и настройка:**
   ```bash
   cp .env.example .env
   ```
   Отредактируйте `.env` файл, указав корректные настройки базы данных. В тестовом случае, это:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=swc
   DB_USERNAME=app
   DB_PASSWORD=secret
   ```

5. **Генерация ключа приложения:**
   ```bash
   php artisan key:generate
   ```

6. **Миграция базы данных:**
   ```bash
   php artisan migrate
   ```

7. **Запуск сервера:**
   Если все шаги выполнены правильно, приложение должно быть доступно по адресу: [http://localhost:8000](http://localhost:8000)

---

## API Документация

### События (Events):

1. **Создание события**:
    - **Endpoint**: `/api/event`
    - **Method**: `POST`
    - **Headers**: `Authorization: Bearer <token>`
    - **Body**:
      ```json
      {
        "title": "title_event",
        "text": "description_event"
      }
      ```

2. **Получение всех событий, где пользователь не принимает участия**:
    - **Endpoint**: `/api/events?filter[not-me]=true`
    - **Method**: `GET`
    - **Headers**: `Authorization: Bearer <token>`

3. **Получение событий, где пользователь участник**:
    - **Endpoint**: `/api/events?filter[participating]=true`
    - **Method**: `GET`
    - **Headers**: `Authorization: Bearer <token>`

4. **Получение конкретного события**:
    - **Endpoint**: `/api/event/<event_id>`
    - **Method**: `GET`
    - **Headers**: `Authorization: Bearer <token>`

5. **Присоединение к событию**:
    - **Endpoint**: `/api/event/<event_id>/join`
    - **Method**: `POST`
    - **Headers**: `Authorization: Bearer <token>`

6. **Покинуть событие**:
    - **Endpoint**: `/api/event/<event_id>/leave`
    - **Method**: `POST`
    - **Headers**: `Authorization: Bearer <token>`

7. **Удаление события**:
    - **Endpoint**: `/api/event/<event_id>`
    - **Method**: `DELETE`
    - **Headers**: `Authorization: Bearer <token>`
    - Удалить событие может только его автор!

---

### Пользователи (Users):

1. **Регистрация**:
    - Важно: Дата рождения не обязательный параметр! Пароль минимум 8 символов
    - **Endpoint**: `/api/register`
    - **Method**: `POST`
    - **Body**:
      ```json
      {
        "login": "test-user",
        "first_name": "John",
        "last_name": "Doe",
        "birth_date": "1999-01-01",
        "password": "secret123"
      }
      ```

2. **Получение информации о текущем пользователе**:
    - **Endpoint**: `/api/me`
    - **Method**: `GET`
    - **Headers**: `Authorization: Bearer <token>`

---

### Аутентификация (Auth):

1. **Аутентификация пользователя**:
    - **Endpoint**: `/api/token`
    - **Method**: `POST`
    - **Body**:
      ```json
      {
        "login": "<login>",
        "password": "<password>"
      }
      ```
---
