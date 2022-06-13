<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
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
    private $typeOperation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOperation;

    /**
     * @ORM\ManyToOne(targetEntity=Contrat::class, inversedBy="operations",cascade={"persist"})
     */
    private $contrats;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="operations")
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeOperation(): ?string
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(string $typeOperation): self
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeInterface $dateOperation): self
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    public function getContrats(): ?Contrat
    {
        return $this->contrats;
    }

    public function setContrats(?Contrat $contrats): self
    {
        $this->contrats = $contrats;

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
