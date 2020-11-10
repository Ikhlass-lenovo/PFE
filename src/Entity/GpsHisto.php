<?php

namespace App\Entity;

use App\Repository\GpsHistoRepository;
use Doctrine\ORM\Mapping as ORM;
use \Datetime;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=GpsHistoRepository::class)
 */
class GpsHisto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"default"})
         */ 
    private $id;

    /**
     * @ORM\Column(type="integer")
      * @Serializer\Groups({"default"})
         */
    private $id_android;

    /**
     * @ORM\Column(type="string", length=255)
      * @Serializer\Groups({"default"})
         */
    private $ip_wan;

    /**
     * @ORM\Column(type="string", length=255)
      * @Serializer\Groups({"default"})
         */
    private $ip_mac;

    /**
     * @ORM\Column(type="string", length=255)
      * @Serializer\Groups({"default"})
         */
    private $nom_user;

    /**
     * @ORM\Column(type="string", length=255)
      * @Serializer\Groups({"default"})
         */
    private $nom_machine;

    /**
     * @ORM\Column(type="string", length=255)
      * @Serializer\Groups({"default"})
         */

    private $localisation;

    /**
     * @ORM\Column(type="datetime")
       * @Serializer\Groups({"default"})
         */
   
    private $date_update;

   
    /**
     * @ORM\Column(type="string", length=255)
       * @Serializer\Groups({"default"})
         */
     


    private $ip_lan;

    /**
     * @ORM\Column(type="datetime")
      * @Serializer\Groups({"default"})
         */ 
     
    private $date_install;

    /**
     * @ORM\Column(type="float")
      * @Serializer\Groups({"default"})
         */
     
    private $latitude_gps;

    /**
     * @ORM\Column(type="float")
      * @Serializer\Groups({"default"})
         
     */
    private $longitude_gps;

    /**
     * @ORM\Column(type="float")
      * @Serializer\Groups({"default"})
         */
     
    private $altitude_gps;

    /**
     * @ORM\Column(type="float")
      * @Serializer\Groups({"default"})
         */
    private $accuracy_gps;

    /**
     * @ORM\Column(type="string", length=255)
      * @Serializer\Groups({"default"})
         */


    private $provider_gps;

    /**
     * @ORM\Column(type="float")
       * @Serializer\Groups({"default"})
         
     */
    private $bearing_gps;

    /**
     * @ORM\Column(type="float")
      * @Serializer\Groups({"default"})
         */
     
    private $speed_gps;

    /**
     * @ORM\Column(type="string", length=255)
     *
    * @Serializer\Groups({"default"})
    */
    private $elapsedrealtimeannos_gps;

		/**
         * @ORM\ManyToOne(targetEntity="User", inversedBy="gps_histo", cascade={"persist"})
         * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
         * @Serializer\Groups({"default"})
         */
    protected $user;
    // ******************************************************************************************* construct ************************************************************************************

    public function __construct()
    {           
        $this->date_update = new DateTime();
        $this->date_install = new DateTime();
    }
  
// *******

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAndroid(): ?int
    {
        return $this->id_android;
    }

    public function setIdAndroid(int $id_android): self
    {
        $this->id_android = $id_android;

        return $this;
    }

    public function getIpWan(): ?string
    {
        return $this->ip_wan;
    }

    public function setIpWan(string $ip_wan): self
    {
        $this->ip_wan = $ip_wan;

        return $this;
    }

    public function getIpMac(): ?string
    {
        return $this->ip_mac;
    }

    public function setIpMac(string $ip_mac): self
    {
        $this->ip_mac = $ip_mac;

        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nom_user;
    }

    public function setNomUser(string $nom_user): self
    {
        $this->nom_user = $nom_user;

        return $this;
    }

    public function getNomMachine(): ?string
    {
        return $this->nom_machine;
    }

    public function setNomMachine(string $nom_machine): self
    {
        $this->nom_machine = $nom_machine;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate($date_update)
    {
        $this->date_update = $date_update;
    }
   
   

    

    public function getIpLan(): ?string
    {
        return $this->ip_lan;
    }

    public function setIpLan(string $ip_lan): self
    {
        $this->ip_lan = $ip_lan;

        return $this;
    }

    public function getDateInstall()
    {
        return $this->date_install;
    }

    public function setDateInstall( $date_install)
    {
        $this->date_install = $date_install;

        return $this;
    }

    public function getLatitudeGps(): ?float
    {
        return $this->latitude_gps;
    }

    public function setLatitudeGps(float $latitude_gps): self
    {
        $this->latitude_gps = $latitude_gps;

        return $this;
    }

    public function getLongitudeGps(): ?float
    {
        return $this->longitude_gps;
    }

    public function setLongitudeGps(float $longitude_gps): self
    {
        $this->longitude_gps = $longitude_gps;

        return $this;
    }

    public function getAltitudeGps(): ?float
    {
        return $this->altitude_gps;
    }

    public function setAltitudeGps(float $altitude_gps): self
    {
        $this->altitude_gps = $altitude_gps;

        return $this;
    }

    public function getAccuracyGps(): ?float
    {
        return $this->accuracy_gps;
    }

    public function setAccuracyGps(float $accuracy_gps): self
    {
        $this->accuracy_gps = $accuracy_gps;

        return $this;
    }

    public function getProviderGps(): ?string
    {
        return $this->provider_gps;
    }

    public function setProviderGps(string $provider_gps): self
    {
        $this->provider_gps = $provider_gps;

        return $this;
    }

    public function getBearingGps(): ?float
    {
        return $this->bearing_gps;
    }

    public function setBearingGps(float $bearing_gps): self
    {
        $this->bearing_gps = $bearing_gps;

        return $this;
    }

    public function getSpeedGps(): ?float
    {
        return $this->speed_gps;
    }

    public function setSpeedGps(float $speed_gps): self
    {
        $this->speed_gps = $speed_gps;

        return $this;
    }

    public function getElapsedrealtimeannosGps(): ?string
    {
        return $this->elapsedrealtimeannos_gps;
    }

    public function setElapsedrealtimeannosGps(string $elapsedrealtimeannos_gps): self
    {
        $this->elapsedrealtimeannos_gps = $elapsedrealtimeannos_gps;

        return $this;
    }

   public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
