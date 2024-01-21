# Web-приложение для расчета скоринга клиентов

Использовались Docker, PHP 8.2, Symfony 7, MySQL, PHPUnit, Xdebug 3.3, VSCode

## Установка

1. Клонируем проект из репозитория:
`git clone https://github.com/ralfzeit/scoring_symfony.git`

2. Открываем каталог проекта /scoring_symfony в VSCode

3. В терминале запускаем команду для сборки сервисов в docker:
`docker-compose build`

4. Запускаем сервисы в фоне:
`docker-compose up -d`

5. Выполняем миграцию:
`docker exec -ti scoring-php php bin/console doctrine:migrations:migrate`

6. Заполняем БД тестовыми данными:
`docker exec -ti scoring-php php bin/console doctrine:fixtures:load` 
На вопрос системы отвечаем `yes`

Должны выполниться:
> purging database
> loading App\DataFixtures\AppFixtures

Если этого не произошло, выполните команду из п.6 повторно.


## Запуск

1. После установки перейдите по ссылке: `http://127.0.0.1/client`