<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

//    #[ORM\Column(length: 10)]
//    private ?string $days = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)] //faire gaffe pas utiliser DateTimeInterface
    private ?\DateTime $hours = null;

    /**
     * @var Collection<int, Screening>
     */
    #[ORM\OneToMany(targetEntity: Screening::class, mappedBy: 'schedule')]
    private Collection $screenings;

    #[ORM\Column]
    private ?\DateTime $date = null;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
    }


    public function __toString(): string
    {
        if ($this->date === null || $this->hours === null) {
            return 'Non défini';
        }

        $formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, null, null, 'EEEE d MMMM');
        $formattedDate = $formatter->format($this->date);

        return ucfirst($formattedDate) . ' à ' . $this->hours->format('H:i');
    }

    public function getId(): ?int
    {
        return $this->id;
    }



//    public function getDays(): ?string
//    {
//        return $this->days;
//    }
//
//    public function setDays(string $days): static
//    {
//        $this->days = $days;
//
//        return $this;
//    }

    public function getHours(): ?\DateTime
    {
        return $this->hours;
    }

    public function setHours(\DateTime $hours): static
    {
        $this->hours = $hours;

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
            $screening->setSchedule($this);
        }

        return $this;
    }

    public function removeScreening(Screening $screening): static
    {
        if ($this->screenings->removeElement($screening)) {
            // set the owning side to null (unless already changed)
            if ($screening->getSchedule() === $this) {
                $screening->setSchedule(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }
}
