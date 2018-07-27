<?php
/**
 * Created by PhpStorm.
 * User: sidney
 * Date: 5/14/18
 * Time: 1:37 PM
 */

namespace EssentialsBundle\Helpers;


class MailHelper
{
    protected $mailer;
    protected $spool;
    protected $transport;

    public function __construct(\Swift_Mailer $mailer, \Swift_Spool $spool, \Swift_Transport $transport)
    {
        $this->mailer = $mailer;
        $this->spool = $spool;
        $this->transport = $transport;
    }

    /**
     * @param \Swift_Message $message
     * @throws \Swift_TransportException
     * @return int
     */
    public function sendMessage(\Swift_Message $message)
    {
        $this->mailer->send($message);
        return $this->spool->flushQueue($this->transport);
    }
}