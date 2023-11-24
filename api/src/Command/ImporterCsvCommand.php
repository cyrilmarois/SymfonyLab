<?php

namespace App\Command;

use App\Component\Parser\CsvParser;
use App\Component\Parser\Parser;
use App\Entity\Pokemon;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

#[AsCommand(
    name: 'app:import:csv',
    description: 'Import a csv file, all files must be places in public/imports',
    hidden: false,
)]
class ImporterCsvCommand extends Command
{
    public function __construct(
        private ContainerBagInterface $params,
        private ManagerRegistry $doctrine
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'fileName of the file to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fileName = $input->getArgument('filename');
        if ($fileName) {
            $io->note(sprintf('Importing: %s', $fileName));
            $io->writeLn('processing...');
            // we assume all files are in the same default directory
            // public/imports/...
            $fileName = $this->params->get('app.import_dir') . DIRECTORY_SEPARATOR . $fileName;
            $parser = new Parser(new CsvParser(), $fileName);
            $data = $parser->parse();
            // if no error populate db
            $this->doctrine->getRepository(Pokemon::class)
                ->import($data);
        }

        $io->success('Import done.');

        return Command::SUCCESS;
    }
}
