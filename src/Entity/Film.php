<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'film', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $image;

    #[ORM\ManyToOne(inversedBy: 'film')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dub $dub = null;

    #[ORM\ManyToOne(inversedBy: 'film')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * @var Collection<int, Screening>
     */
    #[ORM\OneToMany(targetEntity: Screening::class, mappedBy: 'film')]
    private Collection $screenings;

    #[ORM\Column]
    private ?\DateInterval $duration = null;

    public function __construct()
    {
        $this->image = new ArrayCollection();
        $this->screenings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setFilm($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getFilm() === $this) {
                $image->setFilm(null);
            }
        }

        return $this;
    }

    public function getDub(): ?Dub
    {
        return $this->dub;
    }

    public function setDub(?Dub $dub): static
    {
        $this->dub = $dub;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
            $screening->setFilm($this);
        }

        return $this;
    }

    public function removeScreening(Screening $screening): static
    {
        if ($this->screenings->removeElement($screening)) {
            // set the owning side to null (unless already changed)
            if ($screening->getFilm() === $this) {
                $screening->setFilm(null);
            }
        }

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload)
    {
        if ($this->duration) {
            //temps max autorisé
            $maxHours = 2;
            $maxMinutes = 15;

            // convertir en minute
            $totalMinutes = ($this->duration->h * 60) + $this->duration->i;

            $maxTotalMinutes = ($maxHours * 60) + $maxMinutes;

            if ($totalMinutes > $maxTotalMinutes) {
                $context->buildViolation('La durée ne peut pas dépasser 2 heures et 15 minutes.')
                    ->atPath('duration')
                    ->addViolation();
            }
        }
    }

}
