<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MaterielRepository::class)
 */
class Materiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="5",  minMessage="Le matériel doit contenir au moins 5 caractères")
     */
    private $nomMateriel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(
     *    message = "L'Url '{{ value }}' n'est pas valide",
     * )
     */
    private $lienMarque;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(
     *    message = "L'Url '{{ value }}' n'est pas valide",
     * )
     */
    private $lienAchat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photoMateriel;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieMateriel::class, inversedBy="categorieMat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lienPresentation;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMateriel(): ?string
    {
        return $this->nomMateriel;
    }

    public function setNomMateriel(string $nomMateriel): self
    {
        $this->nomMateriel = $nomMateriel;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getLienMarque(): ?string
    {
        return $this->lienMarque;
    }

    public function setLienMarque(string $lienMarque): self
    {
        $this->lienMarque = $lienMarque;

        return $this;
    }

    public function getLienAchat(): ?string
    {
        return $this->lienAchat;
    }

    public function setLienAchat(string $lienAchat): self
    {
        $this->lienAchat = $lienAchat;

        return $this;
    }

    public function getPhotoMateriel(): ?string
    {
        return $this->photoMateriel;
    }

    public function setPhotoMateriel(string $photoMateriel): self
    {
        $this->photoMateriel = $photoMateriel;

        return $this;
    }

    public function getCategorie(): ?CategorieMateriel
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieMateriel $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function __toString()
    {
        return $this->nomMateriel;
    }

    public function getLienPresentation(): ?string
    {
        return $this->lienPresentation;
    }

    public function setLienPresentation(?string $lienPresentation): self
    {
        $this->lienPresentation = $lienPresentation;

        return $this;
    }
}
