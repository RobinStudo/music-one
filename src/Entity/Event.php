<?php
namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un nom")
     * @Assert\Length(
     *     min=10,
     *     max=80,
     *     minMessage="Le nom doit comporter au minimum {{ limit }} caractères",
     *     maxMessage="Le nom doit comporter au maximum {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Vous devez saisir une description")
     * @Assert\Length(
     *     min=10,
     *     max=2000,
     *     minMessage="La description doit comporter au minimum {{ limit }} caractères",
     *     maxMessage="La description doit comporter au maximum {{ limit }} caractères"
     * )
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank(message="Vous devez saisir une date de début")
     * @Assert\GreaterThan("+2 days", message="Votre événement ne peut démarrer dans moins de 48H")
     * @ORM\Column(type="datetime_immutable")
     */
    private $startAt;

    /**
     * @Assert\NotBlank(message="Vous devez saisir une date de fin")
     * @Assert\GreaterThan(propertyPath="startAt", message="Votre date de fin ne doit pas être antérieur à la date de début")
     * @ORM\Column(type="datetime_immutable")
     */
    private $endAt;

    /**
     * @Assert\Positive(message="Vous devez saisir un nombre positif ou laisser le champ vide pour une capacité ilimitée")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @Assert\Positive(message="Vous devez saisir un nombre positif ou laisser le champ vide pour une entrée gratuite")
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @Assert\NotBlank(message="Vous devez saisir une URL d'image")
     * @Assert\Url(message="Votre URL ne semble pas valide")
     * @Assert\Regex("/.*\.(jpe?g|png)/", message="Votre URL ne semble pas valide")
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    private $pictureFile;

    /**
     * @return mixed
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * @param mixed $pictureFile
     */
    public function setPictureFile(?File $pictureFile): self
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param mixed $pictureUrl
     */
    public function setPictureUrl(string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    private $pictureUrl;
    /**
     * @Assert\NotBlank(message="Vous devez choisir une catégorie")
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @Assert\NotBlank(message="Vous devez choisir un lieu")
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ownedEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="event", orphanRemoval=true)
     */
    private $participants;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Booking $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setEvent($this);
        }

        return $this;
    }

    public function removeParticipant(Booking $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getEvent() === $this) {
                $participant->setEvent(null);
            }
        }

        return $this;
    }
}
