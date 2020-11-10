<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email", "username"})
     * @JMS\ExclusionPolicy("none")
 
 * 
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     * @JMS\Groups({"user:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\NotBlank
    * @JMS\Groups({"user:read"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $password;
     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $nom;
     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $prenom;
     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $adresse;
    
     /**
     * @ORM\Column(type="json")
     * @JMS\Groups({"user:read"})
     */
    private $roles=[];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activationToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPasswordToken;

      /**
     * @ORM\OneToMany(targetEntity=GpsHisto::class, mappedBy="user")
     */
    private $gpsHistos;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
      /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

     public function getActivationToken(): ?string
     {
         return $this->activationToken;
     }

     public function setActivationToken(?string $activationToken): self
     {
         $this->activationToken = $activationToken;

         return $this;
     }

     public function getResetPasswordToken(): ?string
     {
         return $this->resetPasswordToken;
     }

     public function setResetPasswordToken(?string $resetPasswordToken): self
     {
         $this->resetPasswordToken = $resetPasswordToken;

         return $this;
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
     public function getAdresse(): ?string
     {
         return $this->adresse;
     }
 
     public function setAdresse(string $adresse): self
     {
         $this->adresse = $adresse;
 
         return $this;
     }
      public function __construct()
    {
        parent::__construct();
        $this->gpsHistos = new ArrayCollection();    
    }

    public function getGpsHistos(): Collection
    {
        return $this->gpsHistos;
    }

    public function addGpsHisto(GpsHisto $gpsHisto): self
    {
        if (!$this->gpsHistos->contains($gpsHisto)) {
            $this->gpsHistos[] = $gpsHisto;
            $gpsHisto->setUser($this);
        }

        return $this;
    }

    public function removeGpsHisto(GpsHisto $gpsHisto): self
    {
        if ($this->gpsHistos->contains($gpsHisto)) {
            $this->gpsHistos->removeElement($gpsHisto);
            // set the owning side to null (unless already changed)
            if ($gpsHisto->getUser() === $this) {
                $gpsHisto->setUser(null);
            }
        }

        return $this;
    }


}