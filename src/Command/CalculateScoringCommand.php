<?php
/*
 * Консольная команда для расчета скоринга
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Command;

use App\Service\ScoringService;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Класс консольной функции
 */
#[AsCommand(
    name: 'app:calculate-scoring',
    description: 'Выполняет расчет скоринга клиентов.',
    hidden: false,
    aliases: ['app:cs']
)]
class CalculateScoringCommand extends Command
{
    private $scoringService;
    private $doctrine;
    private $entityManager;

    public function __construct(ScoringService $scoringService, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $this->scoringService = $scoringService;
        $this->doctrine = $doctrine;
        $this->entityManager = $entityManager;

        parent::__construct();
    }
    
    /**
     * Конфигурация консольной функции
     */
    protected function configure(): void
    {
        // сообщение помощи команды, отображаемое при запуске команды с опцией "--help"
        $this->setHelp('Данная команда позволяет Вам выполнить расчет скоринга');
        // опциональный аргумент id
        $this->addArgument('id', InputArgument::OPTIONAL, 'ID клиента (целое число) (опционально)');

    }

    /**
     * Запуск консольной функции (без аргумента id - расчет скоринга с записью в БД и детализацией для всех клиентов, с id - для одного)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');

        //Расчет скоринга для всех клиентов
        if(!$id) {
            $output->writeln('<fg=green>Расчет скоринга для всех клиентов</>');
            
            $clients = $this->doctrine->getRepository(Client::class)->findAll();

            if($clients) {
                foreach($clients as $client){
                    $details = $this->scoringService->calculateScoreForConsole(
                        $client->getPhone(), 
                        $client->getEmail(), 
                        $client->getEducationId()->getTitle(), 
                        $client->isAgree()
                    );
                    $output->writeln('ID клиента: '.$client->getId());
                    $output->writeln('Сотовый оператор: '.$details["provider"]);
                    $output->writeln('Домен почты: '.$details["domain"]);
                    $output->writeln('Образование: '.$details["education"]);
                    $output->writeln('Согласие: '.$details["agree"]);
                    $output->writeln('Актуальный скоринг в БД: '.$client->getScore());
                    $output->writeln('<fg=blue>Скоринг (сумма баллов): '.(string)$details["scoring"].'</>');
                    $output->writeln('');

                    $client->setScore($details["scoring"]);
                    $this->entityManager->persist($client);
                    $this->entityManager->flush();
                }
            }
            else {
                $output->writeln('<fg=red>Клиенты не найдены</>');
            }

        }
        //Расчет скоринга для одного клиента
        else if((is_int($id) || ctype_digit($id)) && (int)$id >= 0 ) {
            $output->writeln('<fg=green>Расчет скоринга с детализацией для клиента с id='.$input->getArgument('id').'</>');
            
            $client = $this->doctrine->getRepository(Client::class)->find($id);
            if($client) {
                $details = $this->scoringService->calculateScoreForConsole(
                    $client->getPhone(), 
                    $client->getEmail(), 
                    $client->getEducationId()->getTitle(), 
                    $client->isAgree()
                );
                $output->writeln('Сотовый оператор: '.$details["provider"]);
                $output->writeln('Домен почты: '.$details["domain"]);
                $output->writeln('Образование: '.$details["education"]);
                $output->writeln('Согласие: '.$details["agree"]);
                $output->writeln('Актуальный скоринг в БД: '.$client->getScore());
                $output->writeln('<fg=blue>Скоринг (сумма баллов): '.(string)$details["scoring"].'</>');
                $output->writeln('');

                $client->setScore($details["scoring"]);
                $this->entityManager->persist($client);
                $this->entityManager->flush();
            }
            else {
                $output->writeln('<fg=red>Клиент не найден</>');
            }
        }
        //Если введен неправильный аргумент
        else {
            $output->writeln('<fg=red>Введено некорректное значение id</>');
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }
}