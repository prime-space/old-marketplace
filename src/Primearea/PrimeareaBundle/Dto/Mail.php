<?php namespace Primearea\PrimeareaBundle\Dto;

/*
 * Mail Dto
 *
 * @author Maksim.Salamakhin
 */
class Mail
{
    /** @const string */
    public const STATUS_NEED_SEND = 'need';
    /** @const string */
    public const STATUS_SENT = 'sent';
    /** @const string */
    public const STATUS_ERROR = 'error';

    /*
     * ID
     */
    public $id;
    /*
     * Subject
     */
    public $subject;
    /*
     * Message
     */
    public $message;
    /*
     * To Email
     */
    public $to;
    /*
     * Sending status
     */
    public $status;
    /*
     * Create time
     */
    public $timestamp;

    /*
     * Constructor
     */
    public function __construct()
    {
        $this->timestamp = $this->timestamp ? new \DateTime($this->timestamp) : null;
    }
}
