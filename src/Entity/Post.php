<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="5",  minMessage="Le titre de votre post doit contenir au moins 10 caractères")
     */
    private $titreSujet;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @ORM\GeneratedValue()
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=999)
     * @Assert\Length(min="50",  minMessage="Le message doit contenir au moins 50 caractères")
     */
    private $post;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photoPost;

    /**
     * @ORM\ManyToOne(targetEntity=Abonne::class, inversedBy="posts")
     * @ORM\JoinColumn(name="abonne_id", referencedColumnName="id")
     */
    private $abonne;

    /**
     * @ORM\ManyToOne(targetEntity=CategoriePost::class, inversedBy="catePost")
     * @ORM\JoinColumn(name="categorie_post_id", referencedColumnName="id")
     */
    private $catePost;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieMateriel::class, inversedBy="categorie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categorieMat;

    /**
     * @ORM\ManyToOne(targetEntity=Illustrateur::class, inversedBy="illustrateurPost")
     * @ORM\JoinColumn(nullable=true)
     */
    private $illustrateur;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="livrePost")
     * @ORM\JoinColumn(nullable=true)
     */
    private $livre;

    // fonction pour retourné la date
    public function __toString()
    {
        return $this->dateCreation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreSujet(): ?string
    {
        return $this->titreSujet;
    }

    public function setTitreSujet(string $titreSujet): self
    {
        $this->titreSujet = $titreSujet;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(string $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getPhotoPost(): ?string
    {
        return $this->photoPost;
    }

    public function setPhotoPost(string $photoPost): self
    {
        $this->photoPost = $photoPost;

        return $this;
    }

    public function getAbonne(): ?Abonne
    {
        return $this->abonne;
    }

    public function setAbonne(?Abonne $abonne): self
    {
        $this->abonne = $abonne;

        return $this;
    }

    public function getCatePost(): ?CategoriePost
    {
        return $this->catePost;
    }

    public function setCatePost(?CategoriePost $catePost): self
    {
        $this->catePost = $catePost;

        return $this;
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

    public function getCategorieMat(): ?CategorieMateriel
    {
        return $this->categorieMat;
    }

    public function setCategorieMat(?CategorieMateriel $categorieMat): self
    {
        $this->categorieMat = $categorieMat;

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

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }
}
