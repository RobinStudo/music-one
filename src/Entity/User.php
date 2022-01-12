<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", message="Cette e-mail est déjà rattaché a un compte")
 * @UniqueEntity("displayName", message="Ce nom d'utilisateur est déjà pris")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_BANNED = 'banned';
    const STATUS_PENDING = 'pending';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez saisir votre adresse email")
     * @Assert\Email(message="Cette adresse email ne semble pas valide")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Vous devez saisir votre mot de passe")
     * @Assert\Regex("/^(?=.*[A-Za-z])(?=.*\d)/", message="Votre mot de passe doit contenir un chiffre et une lettre")
     * @Assert\Length(
     *     min=8,
     *     max=30,
     *     minMessage="Votre mot de psse doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Votre mot de passe doit contenir au maximum {{ limit }} caractères"
     * )
     * @Assert\NotCompromisedPassword(message="Ce mot de passe semble compromis")
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur")
     * @Assert\Length(
     *     min=4,
     *     max=30,
     *     minMessage="Votre nom d'utilisateur doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Votre nom d'utilisateur doit contenir au maximum {{ limit }} caractères"
     * )
     * @Assert\Regex("/^[a-zA-Z0-9_]*$/", message="Votre mot de passe doit contenir uniquement des caractères alphanumériques et des underscores")
     * @ORM\Column(type="string", length=30)
     */
    private $displayName;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $status = self::STATUS_ACTIVE;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="owner", orphanRemoval=true)
     */
    private $ownedEvents;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="user", orphanRemoval=true)
     */
    private $bookings;

    public function __construct()
    {
        $this->ownedEvents = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Event[]
     */
    public function getOwnedEvents(): Collection
    {
        return $this->ownedEvents;
    }

    public function addOwnedEvent(Event $ownedEvent): self
    {
        if (!$this->ownedEvents->contains($ownedEvent)) {
            $this->ownedEvents[] = $ownedEvent;
            $ownedEvent->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedEvent(Event $ownedEvent): self
    {
        if ($this->ownedEvents->removeElement($ownedEvent)) {
            // set the owning side to null (unless already changed)
            if ($ownedEvent->getOwner() === $this) {
                $ownedEvent->setOwner(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
            }
        }

        return $this;
    }
}
