<?php

namespace App\Test\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/client/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Client::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'client[name]' => 'Testing',
            'client[surname]' => 'Testing',
            'client[phone]' => 'Testing',
            'client[email]' => 'Testing',
            'client[agree]' => 'Testing',
            'client[education_id]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setName('My Title');
        $fixture->setSurname('My Title');
        $fixture->setPhone('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAgree('My Title');
        $fixture->setEducation_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setName('Value');
        $fixture->setSurname('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setAgree('Value');
        $fixture->setEducation_id('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'client[name]' => 'Something New',
            'client[surname]' => 'Something New',
            'client[phone]' => 'Something New',
            'client[email]' => 'Something New',
            'client[agree]' => 'Something New',
            'client[education_id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/client/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getSurname());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getAgree());
        self::assertSame('Something New', $fixture[0]->getEducation_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setName('Value');
        $fixture->setSurname('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setAgree('Value');
        $fixture->setEducation_id('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/client/');
        self::assertSame(0, $this->repository->count([]));
    }
}
