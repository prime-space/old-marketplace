<?php namespace Primearea\PrimeareaBundle\Repository;

use Primearea\PrimeareaBundle\Dto\Mail;
use Well\DBBundle\DB\DefaultDbClientTrait;
use Well\DBBundle\Exception\EntryNotFoundException;

class MailRepository
{
    use DefaultDbClientTrait;

    /**
     * @return Mail[]
     */
    public function findNeedSend(): array
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT *
FROM mail
WHERE status = :status
ORDER BY id DESC
LIMIT 50
SQL
            )
            ->execute([
                'status' => Mail::STATUS_NEED_SEND,
            ]);

        $mails = [];
        try {
            while ($mail = $statement->fetchObject(Mail::class)) {
                $mails[] = $mail;
            }
        } catch (EntryNotFoundException $e) {
        }

        return $mails;
    }
}
