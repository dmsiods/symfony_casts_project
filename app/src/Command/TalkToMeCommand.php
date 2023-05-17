<?php

namespace App\Command;

use App\Repository\VinylMixRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:talk-to-me',
    description: 'Saying hello command',
)]
class TalkToMeCommand extends Command
{
    public function __construct(private VinylMixRepository $mixRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Your name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Shall I yell?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name') ?: 'whoever you are';
        $yellOption = $input->getOption('yell');

        $message = sprintf('Hey %s!', $name);

        if ($yellOption) {
            $message = strtoupper($message);
        }

        $io->success($message);

        if ($io->confirm('Would you like a recommendation?')) {
            $mixes = $this->mixRepository->findAll();
            $randomMix = $mixes[array_rand($mixes, 1)];
            $io->note('Try this one: ' . $randomMix['title']);
        }

        return Command::SUCCESS;
    }
}
