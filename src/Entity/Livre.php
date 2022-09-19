<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 */
class Livre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="5",  minMessage="Le titre doit contenir au moins 5 caractères")
     */
    private $titreLivre;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateSorti;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lienPourAcheter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couvertureLivre;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $nbrPages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lienPresentation;

    /**
     * @ORM\ManyToOne(targetEntity=Illustrateur::class, inversedBy="livres")
     * @ORM\JoinColumn(name="illustrateur_id", referencedColumnName="id")
     */
    private $illustrateur;


    /**
     * @ORM\ManyToOne(targetEntity=CategorieMateriel::class, inversedBy="livres")
     * @ORM\JoinColumn(name="categorie_materiel_id", referencedColumnName="id")
     */
    private $categorieMateriel;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="livre")
     */
    private $livrePost;

    public function __construct()
    {
        $this->livrePost = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreLivre(): ?string
    {
        return $this->titreLivre;
    }

    public function setTitreLivre(string $titreLivre): self
    {
        $this->titreLivre = $titreLivre;

        return $this;
    }


    public function getDateSorti()
    {
        return $this->dateSorti;
    }

    public function setDateSorti($dateSorti): self
    {
        $this->dateSorti = $dateSorti;

        return $this;
    }

    public function getLienPourAcheter(): ?string
    {
        return $this->lienPourAcheter;
    }

    public function setLienPourAcheter(string $lienPourAcheter): self
    {
        $this->lienPourAcheter = $lienPourAcheter;

        return $this;
    }

    public function getCouvertureLivre()
    {
        return $this->couvertureLivre;
    }

    public function setCouvertureLivre(string $couvertureLivre): self
    {
        $this->couvertureLivre = $couvertureLivre;

        return $this;
    }

    public function getNbrPages()
    {
        return $this->nbrPages;
    }

    public function setNbrPages( $nbrPages): self
    {
        $this->nbrPages = $nbrPages;

        return $this;
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

    public function __toString()
    {
        return $this->titreLivre;
        return $this->illustrateur;
    }


    public function getCategorieMateriel(): ?CategorieMateriel
    {
        return $this->categorieMateriel;
    }

    public function setCategorieMateriel(?CategorieMateriel $categorieMateriel): self
    {
        $this->categorieMateriel = $categorieMateriel;
        return $this;
    }

    public function getIllustrateur(): ?Illustrateur
    {
        return $this->illustrateur;
    }

    public function setIllustrateur(?Illustrateur $illustrateur): self
    {
        $this->illustrateur = $illustrateur;
        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getLivrePost(): Collection
    {
        return $this->livrePost;
    }

    public function addLivrePost(Post $livrePost): self
    {
        if (!$this->livrePost->contains($livrePost)) {
            $this->livrePost[] = $livrePost;
            $livrePost->setLivre($this);
        }

        return $this;
    }

    public function removeLivrePost(Post $livrePost): self
    {
        if ($this->livrePost->contains($livrePost)) {
            $this->livrePost->removeElement($livrePost);
            // set the owning side to null (unless already changed)
            if ($livrePost->getLivre() === $this) {
                $livrePost->setLivre(null);
            }
        }

        return $this;
    }
    
   
}
