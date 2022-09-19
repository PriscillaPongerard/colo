<?php

namespace App\Entity;

use App\Repository\CategorieMaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieMaterielRepository::class)
 */
class CategorieMateriel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomCategorie;

    /**
     * @ORM\OneToMany(targetEntity=Materiel::class, mappedBy="categorie")
     */
    private $categorieMat;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="categorieMateriel")
     */
    private $livres;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="categorieMat")
     */
    private $categorie;

    
    public function __construct()
    {
        $this->categorieMat = new ArrayCollection();
        $this->catMateriel = new ArrayCollection();
        $this->catMat = new ArrayCollection();
        $this->categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }
    public function __toString()
    {
        return $this->nomCategorie;
        return $this->categorieMateriel;
    }

    /**
     * @return Collection|Materiel[]
     */
    public function getCategorieMat(): Collection
    {
        return $this->categorieMat;
    }

    public function addCategorieMat(Materiel $categorieMat): self
    {
        if (!$this->categorieMat->contains($categorieMat)) {
            $this->categorieMat[] = $categorieMat;
            $categorieMat->setCategorie($this);
        }
        return $this;
    }

    public function removeCategorieMat(Materiel $categorieMat): self
    {
        if ($this->categorieMat->contains($categorieMat)) {
            $this->categorieMat->removeElement($categorieMat);
            // set the owning side to null (unless already changed)
            if ($categorieMat->getCategorie() === $this) {
                $categorieMat->setCategorie(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivre(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setCategorieMateriel($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->contains($livre)) {
            $this->livres->removeElement($livre);
            // set the owning side to null (unless already changed)
            if ($livre->getCategorieMateriel() === $this) {
                $livre->setCategorieMateriel(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getCatMat(): Collection
    {
        return $this->catMat;
    }

    public function addCatMat(Post $catMat): self
    {
        if (!$this->catMat->contains($catMat)) {
            $this->catMat[] = $catMat;
            $catMat->setCategorieMateriel($this);
        }

        return $this;
    }

    public function removeCatMat(Post $catMat): self
    {
        if ($this->catMat->contains($catMat)) {
            $this->catMat->removeElement($catMat);
            // set the owning side to null (unless already changed)
            if ($catMat->getCategorieMateriel() === $this) {
                $catMat->setCategorieMateriel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Post $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
            $categorie->setCategorieMat($this);
        }

        return $this;
    }

    public function removeCategorie(Post $categorie): self
    {
        if ($this->categorie->contains($categorie)) {
            $this->categorie->removeElement($categorie);
            // set the owning side to null (unless already changed)
            if ($categorie->getCategorieMat() === $this) {
                $categorie->setCategorieMat(null);
            }
        }

        return $this;
    }

}
