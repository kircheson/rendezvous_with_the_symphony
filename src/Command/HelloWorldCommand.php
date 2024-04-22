<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{
    // имя команды (часть после "bin/console")
    protected static $defaultName = 'hello-world';

    protected function configure()
    {
        // описание команды, которое будет отображаться при запуске "php bin/console list"
        $this->setDescription('Outputs "Hello World!"');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // вывод "Hello World!" в консоль
        $output->writeln('Hello World!');

        return Command::SUCCESS;
    }
}
