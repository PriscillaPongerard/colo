<?php

namespace App\Entity;

use App\Repository\CategoriePostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriePostRepository::class)
 */
class CategoriePost
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
    private $categorieSujet;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="catePost")
     */
    private $catePost;


    public function __construct()
    {
        $this->catePost = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieSujet(): ?string
    {
        return $this->categorieSujet;
    }

    public function setCategorieSujet(string $categorieSujet): self
    {
        $this->categorieSujet = $categorieSujet;

        return $this;
    }

    public function __toString()
    {
        return $this->categorieSujet;
    }

    /**
     * @return Collection|Post[]
     */
    public function getCatePosts(): Collection
    {
        return $this->catePosts;
    }

    public function addCatePost(Post $catePost): self
    {
        if (!$this->catePosts->contains($catePost)) {
            $this->catePosts[] = $catePost;
            $catePost->setCatePost($this);
        }

        return $this;
    }

    public function removeCatePost(Post $catePost): self
    {
        if ($this->catePosts->contains($catePost)) {
            $this->catePosts->removeElement($catePost);
            // set the owning side to null (unless already changed)
            if ($catePost->getCatePost() === $this) {
                $catePost->setCatePost(null);
            }
        }

        return $this;
    }
}
