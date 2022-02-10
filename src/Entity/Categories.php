<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
#[UniqueEntity("name", message: "Cette catégorie existe déjà")]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['auto:api'])]
    private $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Automobiles::class, orphanRemoval: true)]
    private $automobiles;

    /**
     * Categories constructor.
     */
    public function __construct()
    {
        $this->automobiles = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Automobiles[]
     */
    public function getAutomobiles(): Collection
    {
        return $this->automobiles;
    }

    /**
     * @param \App\Entity\Automobiles $automobile
     * @return $this
     */
    public function addAutomobile(Automobiles $automobile): self
    {
        if (!$this->automobiles->contains($automobile)) {
            $this->automobiles[] = $automobile;
            $automobile->setCategory($this);
        }

        return $this;
    }

    public function removeAutomobile(Automobiles $automobile): self
    {
        if ($this->automobiles->removeElement($automobile)) {
            // set the owning side to null (unless already changed)
            if ($automobile->getCategory() === $this) {
                $automobile->setCategory(null);
            }
        }

        return $this;
    }
}
