<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Mail;

use Cmf\System\Application;

use Zend\Mail as ZendMail;
use Zend\Mail\Exception\RuntimeException;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Mailer
{
    /**
     * @param string $title
     * @param string $body
     * @param string $toMail
     * @param string|null $toTitle
     * @return bool
     */
    public static function send($title, $body, $toMail, $toTitle = null)
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\Mail');

        $html = new MimePart($body);
        $html->type = "text/html";

        $mailBody = new MimeMessage();
        $mailBody->setParts([$html]);

        $mail = new ZendMail\Message();
        $mail
            ->setSubject($title)
            ->setBody($mailBody)
            ->setSender($config->mailFrom, $config->titleFrom)
            ->addTo($toMail, $toTitle)
            ->setEncoding($config->encoding);

        try {
            //TODO: implement other transports
            $transport = new ZendMail\Transport\Sendmail();
            $transport->send($mail);

            $result = true;
        } catch (RuntimeException $e) {
            $result = false;
        }

        return $result;
    }
}
