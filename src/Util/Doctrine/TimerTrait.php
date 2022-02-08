<?php
namespace App\Util\Doctrine;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait TimerTrait
{
    /**
    * @ORM\Column(type="datetime_immutable")
    */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function generateCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @ORM\PreUpdate
     */
    public function generateUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }
}