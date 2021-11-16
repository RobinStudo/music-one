<?php
namespace App\Service;

use App\Model\Contact;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private array $config;
    private MailerInterface $mailer;

    public function __construct(ParameterBagInterface $parameterBag, MailerInterface $mailer)
    {
        $this->config = $parameterBag->get('mailer');
        $this->mailer = $mailer;
    }

    public function sendSupport(Contact $contact): bool
    {
        return $this->send(
            $this->config['supportAddress'],
            'Nouvelle demande support MusicOne',
            'support',
            [
                'contact' => $contact
            ]
        );
    }

    private function send($to, $subject, $template, $context): bool
    {
        $email = new TemplatedEmail();
        $email->from($this->config['senderAddress']);
        $email->to($to);
        $email->subject($subject);

        $templatePath = sprintf('%s%s.html.twig', $this->config['templateFolder'], $template);
        $email->htmlTemplate($templatePath);
        $email->context($context);

        try{
            $this->mailer->send($email);
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}