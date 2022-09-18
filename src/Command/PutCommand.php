<?php


namespace HSawai\S3ConnectivityChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use HSawai\S3ConnectivityChecker\Service\PutObject;

class PutCommand extends Command
{

   const NAME = 's3:put';
   private $putter;

    public function __construct(PutObject $putter)
    {
        parent::__construct(self::NAME);
        $this->putter = $putter;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        try {
            $this->putter->putObject(getenv('BUCKET'), getenv('FILE_NAME'));
            $output->writeln('success');

            return 0;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return 255;
        }
    }
}