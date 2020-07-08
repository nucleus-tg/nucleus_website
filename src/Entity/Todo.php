<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 */
class Todo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $todoOrder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $daysNeeded;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Devis::class, inversedBy="todos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFinished;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTodoOrder(): ?int
    {
        return $this->todoOrder;
    }

    public function setTodoOrder(int $todoOrder): self
    {
        $this->todoOrder = $todoOrder;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDaysNeeded(): ?int
    {
        return $this->daysNeeded;
    }

    public function setDaysNeeded(int $daysNeeded): self
    {
        $this->daysNeeded = $daysNeeded;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getProject(): ?Devis
    {
        return $this->project;
    }

    public function setProject(?Devis $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getIsFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): self
    {
        $this->isFinished = $isFinished;

        return $this;
    }
}
