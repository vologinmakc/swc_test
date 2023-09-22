-- Создание второй базы данных для тестов
CREATE DATABASE IF NOT EXISTS swc_test;

-- Предоставление прав пользователю `app` для доступа к обоим базам данных
GRANT ALL PRIVILEGES ON swc.* TO 'app'@'%';
GRANT ALL PRIVILEGES ON swc_test.* TO 'app'@'%';
FLUSH PRIVILEGES;
