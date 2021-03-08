<?php namespace Primearea\PrimeareaBundle;

use Primearea\PrimeareaBundle\Exception\PortCannotExecException;
use Psr\Log\LoggerInterface;

class Port
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $appPath;

    /**
     * @param LoggerInterface $logger
     * @param string $appPath
     */
    public function __construct(string $appPath, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->appPath = $appPath;
    }

    /** @throws PortCannotExecException */
    public function execute($port, $args, $mustReturn)
    {
        $sfPortsPath = "{$this->appPath}/primearea.biz/func/sf-ports";
        $phpPath = '/usr/bin/php';

        $argsString = implode(' ', $args);
        $command = "cd $sfPortsPath; $phpPath ./$port.php $argsString 2>&1";

        $this->logger->info('Exec command', ['command' => $command]);
        exec($command, $out);
        $return = empty($out[0]) ? '' : $out[0];
        if ($return !== $mustReturn) {
            $this->logger->critical('Cannot exec', ['lastReturnLine' => $return]);
            throw new PortCannotExecException('Cannot exec command');
        }
    }
}
