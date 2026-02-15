<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: "laboratorios")]
class Laboratory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;


    #[ORM\Column(type: "string", length: 150)]
    private ?string $title = null;


    #[ORM\Column(type: "text")]
    private ?string $description = null;


    #[ORM\Column(type: "string", length: 20)]
    private ?string $difficulty = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Module $module = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $solution;

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(?string $solution): self
    {
        $this->solution = $solution;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }


    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }


    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }


    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;
        return $this;
    }


    public function getPoints(): int
    {
        return $this->points;
    }


    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }


    public function getModule(): ?Module
    {
        return $this->module;
    }


    public function setModule(Module $module): self
    {
        $this->module = $module;
        return $this;
    }
}
