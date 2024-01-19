<?php

namespace App\DataFixtures;

use App\Entity\Education;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        //Добавляем уровни образования
        $education = new Education();
        $education->setTitle('Высшее образование');
        $manager->persist($education);

        $education = new Education();
        $education->setTitle('Специальное образование');
        $manager->persist($education);

        $education = new Education();
        $education->setTitle('Среднее образование');
        $manager->persist($education);

        $manager->flush();
    }
}
