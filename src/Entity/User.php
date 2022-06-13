<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     */
    private $cin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=Structure::class, inversedBy="utilisateurs")
     */
    private $structure;

    /**
     * @ORM\ManyToMany(targetEntity=Contrat::class, mappedBy="utilisateur")
     */
    private $contrats;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="utilisateur")
     */
    private $operations;

    /**
     * @ORM\OneToMany(targetEntity=Actions::class, mappedBy="utilisateur")
     */
    private $actions;

    public function __construct()
    {
        // $this->actions = new ArrayCollection();
        $this->contrats = new ArrayCollection();
        $this->operations = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getAvatarUrl(){
        // return "https://api.adorable.io/avatars/$size/".$this->getNom();
        return "https://ui-avatars.com/api/?name=".$this->getNom().' '.$this->getPrenom();

    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

   public function __toString(){
        return $this->getId().' '.$this->getNom().' '.$this->getPrenom();
    }

   public function getStructure(): ?Structure
   {
       return $this->structure;
   }

   public function setStructure(?Structure $structure): self
   {
       $this->structure = $structure;

       return $this;
   }



   /**
    * @return Collection<int, Contrat>
    */
   public function getContrats(): Collection
   {
       return $this->contrats;
   }

   public function addContrat(Contrat $contrat): self
   {
       if (!$this->contrats->contains($contrat)) {
           $this->contrats[] = $contrat;
           $contrat->addUtilisateur($this);
       }

       return $this;
   }

   public function removeContrat(Contrat $contrat): self
   {
       if ($this->contrats->removeElement($contrat)) {
           $contrat->removeUtilisateur($this);
       }

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
           $operation->setUtilisateur($this);
       }

       return $this;
   }

   public function removeOperation(Operation $operation): self
   {
       if ($this->operations->removeElement($operation)) {
           // set the owning side to null (unless already changed)
           if ($operation->getUtilisateur() === $this) {
               $operation->setUtilisateur(null);
           }
       }

       return $this;
   }

   /**
    * @return Collection<int, Actions>
    */
   public function getActions(): Collection
   {
       return $this->actions;
   }

   public function addAction(Actions $action): self
   {
       if (!$this->actions->contains($action)) {
           $this->actions[] = $action;
           $action->setUtilisateur($this);
       }

       return $this;
   }

   public function removeAction(Actions $action): self
   {
       if ($this->actions->removeElement($action)) {
           // set the owning side to null (unless already changed)
           if ($action->getUtilisateur() === $this) {
               $action->setUtilisateur(null);
           }
       }

       return $this;
   }
    
}
