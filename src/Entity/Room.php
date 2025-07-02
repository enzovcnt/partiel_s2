<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column]
    private ?int $seats = null;

    /**
     * @var Collection<int, Screening>
     */
    #[ORM\OneToMany(targetEntity: Screening::class, mappedBy: 'room', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $screenings;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * @return Collection<int, Screening>
     */
    public function getScreenings(): Collection
    {
        return $this->screenings;
    }

    public function addScreening(Screening $screening): static
    {
        if (!$this->screenings->contains($screening)) {
            $this->screenings->add($screening);
            $screening->setRoom($this);
        }

        return $this;
    }

    public function removeScreening(Screening $screening): static
    {
        if ($this->screenings->removeElement($screening)) {
            // set the owning side to null (unless already changed)
            if ($screening->getRoom() === $this) {
                $screening->setRoom(null);
            }
        }

        return $this;
    }

    #[Assert\Callback]
    public function validateRoom(ExecutionContextInterface $context, $payload): void
    {
        if ($this->number === 1 && $this->seats !== 85) {
            $context->buildViolation('La salle 1 doit avoir exactement 85 places.')
                ->atPath('seats')
                ->addViolation();
        }

        if (in_array($this->number, [2, 3, 4, 5, 6]) && $this->seats !== 65) {
            $context->buildViolation('Les salles 2 à 6 doivent avoir exactement 65 places.')
                ->atPath('seats')
                ->addViolation();
        }
    }
}
