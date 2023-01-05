<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\LogCountRepository;

#[AsCommand(name: 'log:create', description: 'Push the Logs in the Database', aliases: ['l:c'])]
class LogwriteCommand extends Command
{
    protected $createUser = 'create a new user';

    private $logCountRepository;

    public function __construct(LogCountRepository $logCountRepository)
    {
        $this->logCountRepository = $logCountRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp("This command scraps the data from logs");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Log Writting',
            '============',
            '',
        ]);

        $publicDir = $this->getApplication()->getKernel()->getProjectDir() . '/public';

        $file = realpath($publicDir. '/logs.txt');
        // Open the file
        $handle = fopen($file, 'r');
        if (!$handle) {
            throw new \Exception(sprintf('Failed to open file "%s".', $file));
        }

        // Read the file
        while (($line = fgets($handle)) !== false) {
            // Extract the service, timestamp, and status code
            preg_match('/^(\S+) - \[(.*?)\] ".*" (\d+)/', $line, $matches);

            $service = $matches[1] ?? null;
            $timestampS = $matches[2] ?? null;
            $statusCode = $matches[3] ?? null;

            // Split the date and time string into separate parts
            $dateParts = explode(':', $timestampS);
            $date = $dateParts[0];
            $time = $dateParts[1].":".$dateParts[2].":".$dateParts[3];

            // Convert the date string to a valid format
            $date = date_create_from_format('d/M/Y', $date);
            if ($date === false) {
                // The date string is invalid
                throw new \Exception('Invalid date string');
            }
            $date = $date->format('Y-m-d');

            // Convert the time string to a valid format
            $time = date_create_from_format('H:i:s', $time);
            if ($time === false) {
                // The time string is invalid
                throw new \Exception('Invalid time string');
            }
            $time = $time->format('H:i:s');

            // Concatenate the date and time strings into a single string
            $datetimeString = "$date $time";

            $this->logCountRepository->save($service, new \DateTime($datetimeString), $statusCode);

            // Output the extracted fields
            $output->writeln(sprintf('Service: %s', $service));
            $output->writeln(sprintf('Timestamp: %s', $datetimeString));
            $output->writeln(sprintf('Status code: %s', $statusCode));
            $output->writeln('');
        }

        // Close the file
        fclose($handle);


        // outputs a message followed by a "\n"
        $output->writeln('Log File Successfully added in the db');
        return Command::SUCCESS;
    }

}
