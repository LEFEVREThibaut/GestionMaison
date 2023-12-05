<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Duration = null;

    #[ORM\Column]
    private ?bool $Done = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Taches::class)]
    private Collection $TaskDone;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?string $FinalDuration = null;

    public function __construct()
    {
        $this->TaskDone = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->Duration;
    }

    public function setDuration(?\DateTimeInterface $Duration): static
    {
        $this->Duration = $Duration;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->Done;
    }

    public function setDone(bool $Done): static
    {
        $this->Done = $Done;

        return $this;
    }

    /**
     * @return Collection<int, Taches>
     */
    public function getTaskDone(): Collection
    {
        return $this->TaskDone;
    }

    public function addTaskDone(Taches $taskDone): static
    {
        if (!$this->TaskDone->contains($taskDone)) {
            $this->TaskDone->add($taskDone);
            $taskDone->setSession($this);
        }

        return $this;
    }

    public function removeTaskDone(Taches $taskDone): static
    {
        if ($this->TaskDone->removeElement($taskDone)) {
            // set the owning side to null (unless already changed)
            if ($taskDone->getSession() === $this) {
                $taskDone->setSession(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getFinalDuration(): ?string
    {
        return $this->FinalDuration;
    }

    public function setFinalDuration(string $FinalDuration): static
    {
        $this->FinalDuration = $FinalDuration;

        return $this;
    }
}
