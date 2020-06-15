<?php
//src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=true
 *          )
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


/**
 * @ORM\Column(type="string", nullable=true)
 */
protected $nom;



/**
 * @ORM\Column(type="string", nullable=true
 *          )
 */
protected $prenom;


/**
 * @ORM\Column(type="string", nullable=true
 *          )
 */

protected $adresse;






   /**
    * Set nom
    *
    * @param string $nom
    *
   
    */
    public function setNom($Nom)
    {
        $this->nom = $nom;
        return $this;
    }
 
    /**
    * Set prenom
    *
    * @param string $prenom
    *
   
    */
    public function setPrenom($prenom)
    {
        $this->prenom= $prenom;
        return $this;
    }
 
   /**
    * Set adresse
    *
    * @param string $adresse
    *
   
    */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }
/**
    * Set email
    *
   

    * @param string $email
    *
    
    */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
   
   /**
    * Set password
    *
    * @param string $password
    *
    
    */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
 

/**
    * Get Nom
    *
    * @return string
    */
    public function getNom()
    {
        return $this->nom;
    }
 
 /**
    * Get Prenom
    *
    * @return string
    */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
    * Get adresse
    *
    * @return string
    */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
    * Get Email
    *
    * @return string
    */
    public function getEmail()
    {
        return $this->email;
    }

    /**
    * Get Password
    *
    * @return string
    */
    public function getPassword()
    {
        return $this->password;
    }

    /**
    * Get Username
    *
    *@return string
    
    *public function getUsername()
    *{
        *return $this->username;
*} 
**/
    


    /**
     * @ORM\OneToMany(targetEntity=GpsHisto::class, mappedBy="user")
     */
    
    private $gpsHistos;

    public function __construct()
    {
        parent::__construct();
        // your own logic

        $this->gpsHistos = new ArrayCollection();    }

    /**
     * @return Collection|GpsHisto[]
     */
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