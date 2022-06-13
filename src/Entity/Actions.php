<?php

namespace App\Entity;

use App\Repository\ActionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionsRepository::class)
 */
class Actions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeAction;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateAction;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="actions")
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAction(): ?string
    {
        return $this->typeAction;
    }

    public function setTypeAction(string $typeAction): self
    {
        $this->typeAction = $typeAction;

        return $this;
    }

    public function getDateAction(): ?\DateTimeInterface
    {
        return $this->DateAction;
    }

    public function setDateAction(\DateTimeInterface $DateAction): self
    {
        $this->DateAction = $DateAction;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
