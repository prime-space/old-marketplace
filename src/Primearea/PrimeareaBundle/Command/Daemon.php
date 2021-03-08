<?php namespace Primearea\PrimeareaBundle\Command;

use Exception;
use Primearea\PrimeareaBundle\Logger\LogExtraDataKeeper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\LockHandler;
use Well\DBBundle\DB\Client as DbClient;
use Well\DBBundle\DB\Client;
use Well\DBBundle\DB\ClientProvider;
use Wrep\Daemonizable\Command\EndlessCommand;

abstract class Daemon extends EndlessCommand
{
    /** @var LoggerInterface */
    protected $logger;
    /** @var ClientProvider */
    private $dbClientProvider;
    /**
     * @var LogExtraDataKeeper
     */
    private $logExtraDataKeeper;

    public function __construct(
        ClientProvider $dbClientProvider,
        LogExtraDataKeeper $logExtraDataKeeper,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->logger = $logger;
        $this->dbClientProvider = $dbClientProvider;
        $this->logExtraDataKeeper = $logExtraDataKeeper;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logExtraDataKeeper->setData([
            'module' => 'daemon',
            'name' => $this->getName(),
        ]);
        $this->logger->debug("Start {$this->getName()}");

        $lockFileName = preg_replace('/[^\w\d\s]/', '_', $this->getName()) . '.lock';
        $lockHandler = new LockHandler($lockFileName);
        if (!$lockHandler->lock()) {
            $this->logger->critical('Sorry, cannot lock file');

            return;
        }

        $io = new SymfonyStyle($input, $output);

        try {
            while (1) {
                $this->logExtraDataKeeper->setParam('iterationKey', microtime(true));
                $this->do($io);
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
            /*foreach ($this->dbClientProvider->getClients() as $dbClient) {
                $dbClient->close();
            }*/
            // @TODO CHOICE: CLOSE CONNECTIONS OR SHUTDOWN
            $this->logger->critical('Shutdown daemon');
            $this->shutdown();
        }

        $this->logger->debug("Done {$this->getName()}");
    }

    abstract protected function do(SymfonyStyle $io);
}
