<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratRepository::class)
 */
class Contrat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeCentre;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeRecette;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeContrat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeOperation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="date")
     */
    private $dateContrat;

    
    /**
     * @ORM\Column(type="date")
     */
    private $dateSauvegarde;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $redacteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseEmp;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="contrats")
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="blob")
     */
    private $doc;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateModification;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="contrats")
     */
    private $operations;

    /**
     * @ORM\ManyToMany(targetEntity=Acteur::class, mappedBy="contrat")
     */
    private $acteurs;

    public function __construct()
    {
        $this->utilisateur = new ArrayCollection();
        $this->dateSauvegarde = new \DateTime();
        $this->operations = new ArrayCollection();
        $this->acteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCentre(): ?int
    {
        return $this->codeCentre;
    }

    public function setCodeCentre(int $codeCentre): self
    {
        $this->codeCentre = $codeCentre;

        return $this;
    }

    public function getCodeRecette(): ?int
    {
        return $this->codeRecette;
    }

    public function setCodeRecette(int $codeRecette): self
    {
        $this->codeRecette = $codeRecette;

        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(string $typeContrat): self
    {
        $this->typeContrat = $typeContrat;

        return $this;
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateContrat(): ?\DateTimeInterface
    {
        return $this->dateContrat;
    }

    public function setDateContrat(\DateTimeInterface $dateContrat): self
    {
        $this->dateContrat = $dateContrat;

        return $this;
    }

    public function getDateSauvegarde(): ?\DateTimeInterface
    {
        return $this->dateSauvegarde;
    }

    public function setDateSauvegarde(\DateTimeInterface $dateSauvegarde): self
    {
        $this->dateSauvegarde = $dateSauvegarde;

        return $this;
    }

    public function getRedacteur(): ?string
    {
        return $this->redacteur;
    }

    public function setRedacteur(string $redacteur): self
    {
        $this->redacteur = $redacteur;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getAdresseEmp(): ?string
    {
        return $this->adresseEmp;
    }

    public function setAdresseEmp(string $adresseEmp): self
    {
        $this->adresseEmp = $adresseEmp;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(User $utilisateur): self
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur[] = $utilisateur;
        }

        return $this;
    }

    public function removeUtilisateur(User $utilisateur): self
    {
        $this->utilisateur->removeElement($utilisateur);

        return $this;
    }

    public function getDoc()
    {
        return $this->doc;
    }

    public function setDoc($doc): self
    {
        $this->doc = $doc;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return Collection<int, Operation>
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setContrats($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getContrats() === $this) {
                $operation->setContrats(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Acteur>
     */
    public function getActeurs(): Collection
    {
        return $this->acteurs;
    }

    public function addActeur(Acteur $acteur): self
    {
        if (!$this->acteurs->contains($acteur)) {
            $this->acteurs[] = $acteur;
            $acteur->addContrat($this);
        }

        return $this;
    }

    public function removeActeur(Acteur $acteur): self
    {
        if ($this->acteurs->removeElement($acteur)) {
            $acteur->removeContrat($this);
        }

        return $this;
    }

    public function __toString()
    {
         return $this->getCodeCentre();
    }
    
   
}
