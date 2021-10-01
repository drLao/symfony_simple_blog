<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomTextPostCommand extends Command
{
    protected static $defaultName = 'app:random-text-post';
    protected static $defaultDescription = 'Random text post generator';

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('your-name', InputArgument::OPTIONAL, 'Your name')
            ->addOption('caps', null, InputOption::VALUE_NONE, 'All in CAPS')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $yourName = $input->getArgument('your-name');

        if ($yourName) {
            $io->note(sprintf('You passed a name: %s', $yourName));
        }

        $postBits = [
            ' posuit ', ' week ', ' insect ', ' ferrum ', ' aer ', ' dentium ', ' omnis ', ' sublust ', ' vitro ',
            ' mortuus ', ' caracterem ', ' dirige ', ' tunica ', ' perveniunt ', ' nasum ', ' risu ', ' pascuntur ',
            ' statera ', ' exercitium ', ' call ', ' adepto ', ' gavisus ', ' proeorous ', ' continent ',
            ' repentino ', ' vimus ', ' current ', ' postquam ', ' hauriret ', ' fuge ', ' latere ', ' tangerent ',
            ' fieri ', ' fera ', ' vitae ', ' sensi ', ' frigus ', ' figistrus ', ' castra ', ' aquam ', ' obtinuit ',
            ' festinate ', ' violet ', ' celebre ',
        ];

        $postRandomText = "";

        for ($i = 0; $i < 55; $i++) {
            $postRandomText .= $postBits[array_rand($postBits)];
        }

        if ($input->getOption('caps')) {
            $postRandomText = strtoupper($postRandomText);
        }

        $this->logger->info('Random post was generated '.$postRandomText);
        $io->success($postRandomText);

        return Command::SUCCESS;
    }
}
