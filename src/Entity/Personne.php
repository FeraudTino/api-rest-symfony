<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["personne:read", "adresse:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    #[Groups(["personne:read", "adresse:read"])]
    private $nom;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    #[Groups(["personne:read", "adresse:read"])]
    private $prenom;

    #[ORM\ManyToMany(targetEntity: Adresse::class, inversedBy: 'personnes',  cascade: ['persist'])]
    #[Groups("personne:read")]
    private $Adresse;


    public function __construct()
    {
        $this->adresses = new ArrayCollection();
        $this->Adresse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresse(): Collection
    {
        return $this->Adresse;
    }

    public function addAdresse(Adresse $adresse): self
    {
        if (!$this->Adresse->contains($adresse)) {
            $this->Adresse[] = $adresse;
        }

        return $this;
    }

    public function removeAdresse(Adresse $adresse): self
    {
        $this->Adresse->removeElement($adresse);

        return $this;
    }
}
