<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column]
    private ?bool $annule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raison_annulation = null;

    #[ORM\Column(nullable: true)]
    private ?int $age_requis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?Type $type = null;

    /**
     * @var Collection<int, Date>
     */
    #[ORM\OneToMany(targetEntity: Date::class, mappedBy: 'evenement')]
    private Collection $dates;

    public function __construct()
    {
        $this->dates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getAnnule(): ?bool
    {
        return $this->annule;
    }

    public function isAnnule(): ?bool
    {
        return $this->annule;
    }

    public function setAnnule(bool $annule): static
    {
        $this->annule = $annule;

        return $this;
    }

    public function getRaisonAnnulation(): ?string
    {
        return $this->raison_annulation;
    }

    public function setRaisonAnnulation(?string $raison_annulation): static
    {
        $this->raison_annulation = $raison_annulation;

        return $this;
    }

    public function getAgeRequis(): ?int
    {
        return $this->age_requis;
    }

    public function setAgeRequis(?int $age_requis): static
    {
        $this->age_requis = $age_requis;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Date>
     */
    public function getDates(): Collection
    {
        return $this->dates;
    }

    public function addDate(Date $date): static
    {
        if (!$this->dates->contains($date)) {
            $this->dates->add($date);
            $date->setEvenement($this);
        }

        return $this;
    }

    public function removeDate(Date $date): static
    {
        if ($this->dates->removeElement($date)) {
            // set the owning side to null (unless already changed)
            if ($date->getEvenement() === $this) {
                $date->setEvenement(null);
            }
        }

        return $this;
    }
}
