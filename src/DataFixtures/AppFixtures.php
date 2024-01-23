<?php
/*
 * Фикстура для заполнения тестовыми данными
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Education;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Класс фикстуры
 */
class AppFixtures extends Fixture
{
    /**
     * Загрузка тестовых данных в БД
     */
    public function load(ObjectManager $manager): void
    {
        $domains = array(
            0 => "gmail.com",
            1 => "mail.ru",
            2 => "yandex.ru",
            3 => "ttk.ru",
            4 => "microsoft.com",
        );
        $chars = "abcdefghijklmno";

        //Добавляем уровни образования
        $education[0] = new Education();
        $education[0]->setTitle('Высшее образование');
        $manager->persist($education[0]);

        $education[1] = new Education();
        $education[1]->setTitle('Специальное образование');
        $manager->persist($education[1]);

        $education[2] = new Education();
        $education[2]->setTitle('Среднее образование');
        $manager->persist($education[2]);

        for($i = 0; $i < 10; $i++)
        {
            $client = new Client();
            $client->setName('Клиент '.$i);
            $client->setSurname(('Клиентов '.$i));
            $client->setEducationId($education[random_int(0,2)]);
            $client->setEmail(str_shuffle($chars).'@'.$domains[random_int(0,4)]);
            $client->setAgree((bool)random_int(0, 1));
            $client->setPhone('7'.(string)random_int(903,989).(string)random_int(1000000,9999999));
            $client->setScore(0);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
