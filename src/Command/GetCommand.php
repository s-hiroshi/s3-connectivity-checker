<?php


namespace HSawai\S3ConnectivityChecker\Command;

use HSawai\S3ConnectivityChecker\Service\GetObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCommand extends Command
{

    const NAME = 's3:get';
    private GetObject $getter;

    public function __construct(GetObject $getter)
    {
        parent::__construct(self::NAME);
        $this->getter = $getter;
    }

    protected function configure()
    {
        $this->addUsage('Get S3 object.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $result = $this->getter->getObject(getenv('BUCKET'), getenv('FILE_NAME'));
            $output->writeln($result);

            return 0;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return 255;
        }
    }
}