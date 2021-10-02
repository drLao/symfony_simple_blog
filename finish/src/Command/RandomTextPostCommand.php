<?php

namespace App\Command;

use App\Service\RandomTextGeneratorHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomTextPostCommand extends Command
{
    protected static $defaultName = 'app:random-words';
    protected static $defaultDescription = 'Random words generator';

    private $logger;
    private $randomTextGeneratorHelper;

    public function __construct(LoggerInterface $logger, RandomTextGeneratorHelper $randomTextGeneratorHelper)
    {
        $this->logger = $logger;
        $this->randomTextGeneratorHelper = $randomTextGeneratorHelper;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('number-of-words', InputArgument::OPTIONAL, 'Type number of words to generate')
            ->addOption('caps', null, InputOption::VALUE_NONE, 'All in CAPS')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numberOfWords = $input->getArgument('number-of-words');

        $postRandomText = $this->randomTextGeneratorHelper->generateRandomWords($numberOfWords);

        if ($input->getOption('caps')) {
            $postRandomText = strtoupper($postRandomText);
        }

        $this->logger->info('Random post was generated '.$postRandomText);
        $io->success($postRandomText);

        return Command::SUCCESS;
    }
}
