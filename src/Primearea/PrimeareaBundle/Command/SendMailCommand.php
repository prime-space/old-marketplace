<?php namespace Primearea\PrimeareaBundle\Command;

use Primearea\MailerBundle\Postman;
use Primearea\PrimeareaBundle\Dto\Mail;
use Primearea\PrimeareaBundle\Repository\MailRepository;
use Primearea\PrimeareaBundle\Store\MailStore;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use Wrep\Daemonizable\Command\EndlessCommand;

class SendMailCommand extends EndlessCommand
{
    /** @var Postman */
    private $postman;
    /** @var LoggerInterface */
    private $logger;
    /** @var MailRepository */
    private $mailRepository;
    /** @var MailStore */
    private $mailStore;

    /**
     * @param MailRepository $mailRepository
     * @param MailStore $mailStore
     * @param Postman $postman
     * @param LoggerInterface $logger
     */
    public function __construct(
        MailRepository $mailRepository,
        MailStore $mailStore,
        Postman $postman,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->mailRepository = $mailRepository;
        $this->mailStore = $mailStore;
        $this->postman = $postman;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setName('primearea:send-mail')
            ->setDescription('Send mails')
            ->setHelp('Send mails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Start send-mail</info>');
        $lockHandler = new LockHandler('primearea_send_mail.lock');
        if (!$lockHandler->lock()) {
            $this->logger->error('Sorry, cannot lock file', [get_class()]);

            return;
        }

        $mails = $this->mailRepository->findNeedSend();
        foreach ($mails as $mail) {
            if ($this->postman->send($mail->subject, $mail->message, $mail->to)) {
                $status = Mail::STATUS_SENT;
                $output->writeln("<info>Mail with ID #{$mail->id} sent</info>");
            } else {
                $status = Mail::STATUS_ERROR;
                $output->writeln("<error>Mail with ID #{$mail->id} NOT sent</error>");
            }
            $this->mailStore->setStatus($mail, $status);
        }

        $output->writeln('<info>Done</info>');
    }
}
