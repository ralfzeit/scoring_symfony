<?php

namespace App\Command;
namespace App\Service\ScoringService;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'app:calculate-scoring',
    description: 'Выполняет расчет скоринга клиентов.',
    hidden: false,
    aliases: ['app:cs']
)]
class CalculateScoringCommand extends Command
{
    protected function configure(): void
    {
        // сообщение помощи команды, отображаемое при запуске команды с опцией "--help"
        $this->setHelp('Данная команда позволяет Вам выполнить расчет скоринга');
        $this->addArgument('id', InputArgument::OPTIONAL, 'ID клиента (целое число) (опционально)');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        if(!$id) 
            $output->writeln('Расчет скоринга для всех клиентов');
        else if ((is_int($id) || ctype_digit($id)) && (int)$id >= 0 )
            $output->writeln('Client ID: '.$input->getArgument('id'));
        else return Command::INVALID;

        return Command::SUCCESS;
    }
}