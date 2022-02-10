<?php

namespace App\Entity;

use App\Repository\AutomobilesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AutomobilesRepository::class)]
class Automobiles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['auto:api', 'autos:api'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['auto:api', 'autos:api'])]
    #[Assert\NotBlank]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['auto:api'])]
    private $colors;

    #[ORM\ManyToOne(targetEntity: Categories::class, inversedBy: 'automobiles')]
    #[Groups(['auto:api'])]
    private $category;

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
     * @return string|null
     */
    public function getColors(): ?string
    {
        return $this->colors;
    }

    /**
     * @param string|null $colors
     * @return $this
     */
    public function setColors(?string $colors): self
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @return \App\Entity\Categories|null
     */
    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    /**
     * @param \App\Entity\Categories|null $category
     * @return $this
     */
    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

}
