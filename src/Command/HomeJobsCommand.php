<?php


namespace App\Command;


use App\Manager\ScheduledHomeJobManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class HomeJobsCommand
 *
 * @package App\Command
 */
class HomeJobsCommand extends Command
{

    /**
     * @var ScheduledHomeJobManager
     */
    private $homeJobManager;

    /**
     * HomeJobsCommand constructor.
     *
     * @param ScheduledHomeJobManager $homeJobManager
     * @param string $name
     */
    public function __construct(ScheduledHomeJobManager $homeJobManager, string $name = 'app:generate-home-jobs')
    {
        parent::__construct($name);
        $this->homeJobManager = $homeJobManager;
    }

    /**
     * Configure function.
     */
    protected function configure()
    {
        $this->setDescription('Generates home jobs by scheduled home jobs');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $io = new SymfonyStyle($input, $output);

        if ($this->homeJobManager->generateHomeJobs()) {
            $io->success('HomeJobs have been created successfully!');
        } else {
            $io->error('Error!');
        }
    }
}