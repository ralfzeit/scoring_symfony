<?php
/*
 * Тесты для скоринг-сервиса
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Test\Service;


use App\Service\ScoringService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScoringServiceTest extends WebTestCase
{
    private KernelBrowser $client;
    private ScoringService $scoringService;

    /**
     * Первоначальная конфигурация для тестов
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->scoringService = static::getContainer()->get(ScoringService::class);
    }

    /**
     * Тест расчета баллов за оператора
     */
    public function testGetProviderScore()
    {
        self::assertSame(10, $this->scoringService->getProdiverScore('79239992345'));
        self::assertSame(5, $this->scoringService->getProdiverScore('79069992345'));
        self::assertSame(3, $this->scoringService->getProdiverScore('79139992345'));
        self::assertSame(1, $this->scoringService->getProdiverScore('79999992345'));
    }

    /**
     * Тест расчета баллов за домен
     */
    public function testGetDomainScore()
    {
        self::assertSame(3, $this->scoringService->getDomainScore('test@mai.ru'));
        self::assertSame(6, $this->scoringService->getDomainScore('test@mail.ru'));
        self::assertSame(6, $this->scoringService->getDomainScore('test@mail.com'));
        self::assertSame(8, $this->scoringService->getDomainScore('test@yandex.ru'));
        self::assertSame(8, $this->scoringService->getDomainScore('test@yandex.com'));
        self::assertSame(10, $this->scoringService->getDomainScore('test@gmail.ru'));
        self::assertSame(10, $this->scoringService->getDomainScore('test@gmail.com'));
    }

    /**
     * Тест расчета баллов за согласие
     */
    public function testGetAgreeScore()
    {
        self::assertSame(0, $this->scoringService->getAgreeScore(false));
        self::assertSame(4, $this->scoringService->getAgreeScore(true));
        self::assertSame(0, $this->scoringService->getAgreeScore(0));
        self::assertSame(4, $this->scoringService->getAgreeScore(1));
    }

    /**
     * Тест расчета баллов за образование
     */
    public function testGetEducationScore()
    {
        self::assertSame(15, $this->scoringService->getEducationScore('Высшее образование'));
        self::assertSame(10, $this->scoringService->getEducationScore('Специальное образование'));
        self::assertSame(5, $this->scoringService->getEducationScore('Среднее образование'));
    }

    /**
     * Тест расчета скоринга во время регистрации
     */
    public function testCalculateScoreReg()
    {
        self::assertSame('39', $this->scoringService->calculateScoreReg('79236431111','test@gmail.com','Высшее образование',true));
        self::assertSame('20', $this->scoringService->calculateScoreReg('79066431111','test@mail.ru','Среднее образование',true));
        self::assertSame('30', $this->scoringService->calculateScoreReg('79036431111','test@gmail.com','Высшее образование',false));
        self::assertSame('30', $this->scoringService->calculateScoreReg('79136431111','test@yandex.ru','Высшее образование',true));
        self::assertSame('23', $this->scoringService->calculateScoreReg('79136431111','test@gmail.com','Специальное образование',false));
    }

    /**
     * Тест расчета скоринга для консольной команды
     */
    public function testCalculateScoreForConsole()
    {
        $details = array(
            "provider" => 10,
            "domain" => 10,
            "education" => 15,
            "agree" => 4,
            "scoring" => '39',
        );
        self::assertSame($details, $this->scoringService->calculateScoreForConsole('79236431111','test@gmail.com','Высшее образование',true));
    }

}