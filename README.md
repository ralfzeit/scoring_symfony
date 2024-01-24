[Задание](https://github.com/ralfzeit/scoring_symfony/blob/main/TASK.md)

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
`docker exec -ti scoring-php php bin/console doctrine:fixtures:load --append`  
На вопрос системы отвечаем `yes`  
**Вся существующая информация в БД будет удалена**  

Должно выполниться:
> loading App\DataFixtures\AppFixtures

Если этого не произошло, выполните команду из п.6 повторно.


## Запуск

1. После установки перейдите по ссылке:  
`http://127.0.0.1/client`

2. Консольная функция `app:calculate-scoring` (или `app:cs`) выполняет расчет скоринга для всех клиентов. Также может принимать в качестве аргумента id клиента и расчитать скоринг только для него. Выводит в консоль актуальный скоринг в БД + выводит скоринг с детализацией в консоль.  
**Пример выполнения:**  
**Расчет для всех клиентов:**  
`docker exec -ti scoring-php php bin/console app:calculate-scoring`  
или  
`docker exec -ti scoring-php php bin/console app:cs`  
**Расчет для клиента с id=5:**  
`docker exec -ti scoring-php php bin/console app:calculate-scoring 5`  
или  
`docker exec -ti scoring-php php bin/console app:cs 5`  

## Тестирование

1. Создание схемы тестовой БД:  
`docker exec -ti scoring-php php bin/console --env=test doctrine:schema:create`  

2. Запуск тестов:  
`docker exec -ti scoring-php php bin/phpunit`

## Остановка Docker

`docker-compose stop`  
