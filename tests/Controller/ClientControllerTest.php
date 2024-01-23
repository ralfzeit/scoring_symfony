<?php
/*
 * Тесты для контроллера клиентов
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Test\Controller;

use App\Entity\Client;
use App\Entity\Education;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;

class ClientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private EntityRepository $repositoryEdu;
    private string $path = '/client/';
    private array $edu;

    /**
     * Первоначальная конфигурация для тестов
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Client::class);
        $this->repositoryEdu = $this->manager->getRepository(Education::class);

        $this->clearClientTable();
        $this->clearEducationTable();

        $education = new Education();
        $education->setTitle('Высшее образование');
        $this->manager->persist($education);

        $education = new Education();
        $education->setTitle('Специальное образование');
        $this->manager->persist($education);

        $education = new Education();
        $education->setTitle('Среднее образование');
        $this->manager->persist($education);

        $this->manager->flush();

        $this->edu = $this->repositoryEdu->findAll();
    }

    /**
     * Удаление всех клиентов
     */
    protected function clearClientTable(): void
    {
        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    /**
     * Удаление всех видов образования
     */
    protected function clearEducationTable(): void
    {
        foreach ($this->repositoryEdu->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    /**
     * Тест загрузки списка клиентов
     */
    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Скоринг клиентов');
    }

    /**
     * Тест регистрации клиента
     */
    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Сохранить', [
            'client[name]' => 'Test',
            'client[surname]' => 'Testov',
            'client[phone]' => '78005553535',
            'client[email]' => 'test@mail.ru',
            'client[agree]' => true,
            'client[education_id]' => $this->edu[0]->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));

        $fixture = $this->repository->findAll();

        self::assertSame('Test', $fixture[0]->getName());
        self::assertSame('Testov', $fixture[0]->getSurname());
        self::assertSame('78005553535', $fixture[0]->getPhone());
        self::assertSame('test@mail.ru', $fixture[0]->getEmail());
        self::assertSame(true, $fixture[0]->isAgree());
        self::assertSame($this->edu[0]->getId(), $fixture[0]->getEducationId()->getId());
        self::assertSame(26, $fixture[0]->getScore());
    }

    /**
     * Тест отображения клиента
     */
    public function testShow(): void
    {
        $fixture = new Client();
        $fixture->setName('Test');
        $fixture->setSurname('Testov');
        $fixture->setPhone('78005553535');
        $fixture->setEmail('test@mail.ru');
        $fixture->setAgree(true);
        $fixture->setEducationId($this->edu[0]);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Клиент '.$fixture->getId());
    }

    /**
     * Тест редактирования клиента
     */
    public function testEdit(): void
    {
        $fixture = new Client();
        $fixture->setName('Алексей');
        $fixture->setSurname('Третьяков');
        $fixture->setPhone('79990019090');
        $fixture->setEmail('alex@mail.ru');
        $fixture->setAgree(true);
        $fixture->setEducationId($this->edu[0]);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Сохранить', [
            'client[name]' => 'Иван',
            'client[surname]' => 'Иванов',
            'client[phone]' => '78005553535',
            'client[email]' => 'bulba@gmail.com',
            'client[agree]' => false,
            'client[education_id]' => $this->edu[1]->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        $fixture = $this->repository->findAll();

        self::assertSame('Иван', $fixture[0]->getName());
        self::assertSame('Иванов', $fixture[0]->getSurname());
        self::assertSame('78005553535', $fixture[0]->getPhone());
        self::assertSame('bulba@gmail.com', $fixture[0]->getEmail());
        self::assertSame(false, $fixture[0]->isAgree());
        self::assertSame($this->edu[1]->getId(), $fixture[0]->getEducationId()->getId());
    }

}
