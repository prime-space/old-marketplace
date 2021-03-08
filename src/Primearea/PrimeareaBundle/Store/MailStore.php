<?php namespace Primearea\PrimeareaBundle\Store;

use Primearea\PrimeareaBundle\Dto\Mail;
use Well\DBBundle\DB\DefaultDbClientTrait;

class MailStore
{
    use DefaultDbClientTrait;

    public function setStatus(Mail $mail, string $status) : void
    {
        $this->defaultDbClient->prepare(<<<SQL
UPDATE mail
SET status = :status
WHERE id = :id
SQL
        )->execute([
            'status' => $status,
            'id' => $mail->id
        ]);
    }
}
