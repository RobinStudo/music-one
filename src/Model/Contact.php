<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    const TOPIC = [
        'Question générale sur la plateforme' => 'generic',
        'Réclamation commerciale' => 'claim',
        'Signalement d\'un utilisateur' => 'alert',
        'Autres' => 'other',
    ];

    /**
     * @Assert\NotBlank(message="Vueillez entrer votre nom")
     * @Assert\Length(
     *     min=2,
     *     max=64,
     *     minMessage="Votre nom doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Votre nom doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private string $name;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner votre e-mail")
     * @Assert\Email(message="Cette e-mail n'est pas valide")
     */
    private string $email;


    private string $phone;

    /**
     * @Assert\NotBlank(message="Vous devez choisir un sujet")
     */
    private string $topic;

    /**
     * @Assert\NotBlank(message="Vueillez entrer votre message")
     * @Assert\Length(
     *     min=20,
     *     max=1200,
     *     minMessage="Votre message doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Votre message doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private string $message;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }


    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

}