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

        //Расчет скоринга для всех клиентов
        if(!$id) 
            $output->writeln('<fg=green>Расчет скоринга для всех клиентов</>');
        //Расчет скоринга для одного клиента
        else if ((is_int($id) || ctype_digit($id)) && (int)$id >= 0 )
            $output->writeln('<fg=blue>Расчет скоринга для клиента с id='.$input->getArgument('id'));
        //Если введен неправильный аргумент
        else {
            $output->writeln('<fg=red>Введено некорректное значение id</>');
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }
}