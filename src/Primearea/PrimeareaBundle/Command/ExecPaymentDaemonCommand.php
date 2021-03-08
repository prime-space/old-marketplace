<?php namespace Primearea\PrimeareaBundle\Command;

use Primearea\PrimeareaBundle\Logger\LogExtraDataKeeper;
use Primearea\PrimeareaBundle\MessageBroker;
use Primearea\PrimeareaBundle\Transaction\ExecutorProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Well\DBBundle\DB\ClientProvider;

class ExecPaymentDaemonCommand extends Daemon
{
    private $messageBroker;
    private $executorProvider;

    protected function configure()
    {
        $this->setName('primearea:exec-payment-daemon');
    }
    public function __construct(
        MessageBroker $messageBroker,
        ClientProvider $dbClientProvider,
        LogExtraDataKeeper $logExtraDataKeeper,
        ExecutorProvider $executorProvider,
        LoggerInterface $logger
    ) {
        parent::__construct($dbClientProvider, $logExtraDataKeeper, $logger);
        $this->messageBroker = $messageBroker;
        $this->executorProvider = $executorProvider;
    }

    protected function do(SymfonyStyle $io)
    {
        $message = $this->messageBroker->getMessage(MessageBroker::QUEUE_EXEC_PAYMENT_NAME);

        if (!in_array($message['type'], ['order', 'payment', 'addmoney'], true)) {
            $this->logger->critical("Unknown application");

            return;
        }
        $executor = $this->executorProvider->getExecutor($message['type']);
        $executor->success($message['id']);
        $this->logger->info(
            'Transaction executed',
            ['id' => $message['id'], 'executor' => get_class($executor)]
        );
    }
}
