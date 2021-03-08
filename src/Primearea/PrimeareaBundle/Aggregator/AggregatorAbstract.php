<?php namespace Primearea\PrimeareaBundle\Aggregator;


use Primearea\PrimeareaBundle\Dto\Cashout;
use Primearea\PrimeareaBundle\Exception\WithdrawException;
use Primearea\PrimeareaBundle\PaymentAccountFetcher;
use Primearea\PrimeareaBundle\Repository\UserRepository;
use Psr\Log\LoggerInterface;

abstract class AggregatorAbstract implements AggregatorInterface
{
    protected $userRepository;
    protected $logger;
    protected $paymentAccountFetcher;

    public function __construct(
        UserRepository $userRepository,
        PaymentAccountFetcher $paymentAccountFetcher,
        LoggerInterface $logger
    ) {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
        $this->paymentAccountFetcher = $paymentAccountFetcher;
    }

    /**
     * @param Cashout $cashout
     * @param $purseField
     * @return string
     * @throws WithdrawException
     */
    public function getPurseAbstract(Cashout $cashout, string $purseField): string
    {

        $user = $user = $this->userRepository->find($cashout->userId);
        if (null === $user) {
            throw new WithdrawException('User not found');
        }

        $purse = $user->$purseField;

        if (empty($purse)) {
            throw new WithdrawException('No purse');
        }

        return $purse;
    }

    public function calculateWithdraw($amount, $fee): array
    {
        bcscale(2);
        $feeAmount = bcdiv(bcmul($amount, $fee), 100);
        $amount = bcsub($amount, $feeAmount);

        return [$amount, $feeAmount];
    }
}
