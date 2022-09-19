<?php

namespace App\Entity;

use App\Repository\IllustrateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=IllustrateurRepository::class)
 */
class Illustrateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="5",  minMessage="L'illustrateur doit contenir au moins 5 caractères")
     */
    private $nomIllustrateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(
     *    message = "L'Url '{{ value }}' n'est pas valide",
     * )
     */
    private $lienSite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(
     *    message = "L'Url '{{ value }}' n'est pas valide",
     * )
     */
    private $lienFacebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(
     *    message = "L'Url '{{ value }}' n'est pas valide",
     * )
     */
    private $lienInsta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $chaineYoutube;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="illustrateur")
     */
    private $livres;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="illustrateur")
     */
    private $illustrateurPost;


    public function __construct()
    {
        $this->livres = new ArrayCollection();
        $this->illustrateurPost = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomIllustrateur(): ?string
    {
        return $this->nomIllustrateur;
    }

    public function setNomIllustrateur(string $nomIllustrateur): self
    {
        $this->nomIllustrateur = $nomIllustrateur;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLienSite(): ?string
    {
        return $this->lienSite;
    }

    public function setLienSite(string $lienSite): self
    {
        $this->lienSite = $lienSite;

        return $this;
    }

    public function getLienFacebook(): ?string
    {
        return $this->lienFacebook;
    }

    public function setLienFacebook(?string $lienFacebook): self
    {
        $this->lienFacebook = $lienFacebook;

        return $this;
    }

    public function getLienInsta(): ?string
    {
        return $this->lienInsta;
    }

    public function setLienInsta(?string $lienInsta): self
    {
        $this->lienInsta = $lienInsta;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getChaineYoutube(): ?string
    {
        return $this->chaineYoutube;
    }

    public function setChaineYoutube(?string $chaineYoutube): self
    {
        $this->chaineYoutube = $chaineYoutube;

        return $this;
    }

        public function __toString()
    {
        return $this->nomIllustrateur;
        return $this->id;
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
        if (!$this->livre->contains($livre)) {
            $this->livre[] = $livre;
            $livre->setIllustrateur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livre->contains($livre)) {
            $this->livre->removeElement($livre);
            // set the owning side to null (unless already changed)
            if ($livre->getIllustrateur() === $this) {
                $livre->setIllustrateur(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getIllustrateurs(): Collection
    {
        return $this->illustrateurPost;
    }

    public function addIllustrateur(Post $illustrateur): self
    {
        if (!$this->illustrateurPost->contains($illustrateur)) {
            $this->illustrateurPost[] = $illustrateur;
            $illustrateur->setIllustrateur($this);
        }

        return $this;
    }

    public function removeIllustrateur(Post $illustrateur): self
    {
        if ($this->illustrateurPost->contains($illustrateur)) {
            $this->illustrateurPost->removeElement($illustrateur);
            // set the owning side to null (unless already changed)
            if ($illustrateur->getIllustrateur() === $this) {
                $illustrateur->setIllustrateur(null);
            }
        }

        return $this;
    }
   
}
