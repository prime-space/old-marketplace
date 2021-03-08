<?php namespace Primearea\PrimeareaBundle;

use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\Repository\PaymentAccountRepository;
use Symfony\Component\HttpFoundation\ParameterBag;

class AdminApi
{
    private $paymentAccountRepository;

    public function __construct(PaymentAccountRepository $paymentAccountRepository)
    {
        $this->paymentAccountRepository = $paymentAccountRepository;
    }

    public function syncAccountMethod(ParameterBag $post): array
    {
        $account = PaymentAccountDto::create(
            $post->get('paymentSystemId'),
            $post->get('name'),
            json_decode($post->get('config'), true),
            $post->get('weight'),
            explode(',', $post->get('enabled'))
        );
        $account->id = $post->get('id');

        $this->paymentAccountRepository->insertOnDuplicateUpdate($account);

        return [];
    }

    public function getAccountStatMethod(ParameterBag $post): array
    {
        $data = [
            'using' => $this->paymentAccountRepository->getUsingStat(),
            'turnover' => $this->paymentAccountRepository->getTurnover(),
        ];

        return $data;
    }
}
