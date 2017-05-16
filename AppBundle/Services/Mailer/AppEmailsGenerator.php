<?php
namespace AppBundle\Services\Mailer;

use AppBundle\Entity\Letter;
use Twig_Environment as Environment;

class AppEmailsGenerator
{

    protected $mailer;
    protected $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendLetter(Letter $letter)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('New letter')
            ->setFrom('alexst2501@gmail.com')
            ->setTo($letter->getEmail())
            ->setBody(
                $this->twig->render(
                    '@App/email.html.twig',
                    ['letter' => $letter]
                ),
                'text/html'
            );
        return $this->mailer->send($message);
    }
}