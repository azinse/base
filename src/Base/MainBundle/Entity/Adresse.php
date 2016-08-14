<?php

/*
 * @Zinsè ATENOUKON
 * 2016  *
 */
namespace Base\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
/**
 * Adresse: table des adresses
 *@author zinse
 * 
 * @ORM\Table(name="adresse")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\AdresseRepository")
 */
class Adresse {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="adresse_facturation", type="boolean", nullable=true)
     */
    private $adresse_facturation;
    
    /**
     * @var string $entite
     * @ORM\Column(name="entite", type="string", nullable=true)
     */
    private $entite;
    
    /**
     * @var integer $entite_id
     *
     * @ORM\Column(name="entite_id", type="integer",nullable=true)
     */
    private $entite_id;
    /**
     * @var string $adresse_1
     * 
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;
    
    /**
     * @var string $localite
     *
     * @ORM\Column(name="localite", type="string", length=255, nullable=true)
     */
    private $localite;
    
    /**
     * @var string $departement
     *
     * @ORM\Column(name="departement", type="string", length=255, nullable=true)
     */
    private $departement;
    
    /**
     * @var string $region
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region;
    
    /**
     * @var string $code_postal
     *
     * @ORM\Column(name="code_postal", type="string", length=10, nullable=true)
     */
    private $code_postal;
    
    /**
     * @var string $rue
     *
     * @ORM\Column(name="numero_rue", type="string", length=4, nullable=true)
     */
    private $numero_rue;
    
    /**
     * @var string $rue
     *
     * @ORM\Column(name="rue", type="string", length=255, nullable=true)
     */
    private $rue;
    
    /**
     * @var string $latitude
     *
     * @ORM\Column(name="latitude", type="string", length=20, nullable=true)
     */
    private $latitude;
    
    /**
     * @var string $longitude
     *
     * @ORM\Column(name="longitude", type="string", length=20, nullable=true)
     */
    private $longitude;
    
    /**
     * @var string $code_pays
     *@Assert\Country()
     * @ORM\Column(name="code_pays", type="string", length=3, nullable=true)
     */
    private $code_pays;
    
    /**
     * @var string $pays
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;     
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $date_creation;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $date_modification;
    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     * })
     */
    private $auteur;
    
    public function __construct() {
        // On initialiser les date de création et modification
        $this->setDateCreation(new \DateTime());
        if ($this->getDateModification() == null) {
            $this->setDateModification(new \DateTime());
        }
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Adresse
     */
    public function setCodePostal($codePostal)
    {
        $this->code_postal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Adresse
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }
    /**
     * 
     * @return adresse complete
     */
     public function __toString()
    {
        return $this->adresse;
    }
    
    /**
     * Set adresseFacturation
     *
     * @param boolean $adresseFacturation
     *
     * @return Adresse
     */
    public function setAdresseFacturation($adresseFacturation)
    {
        $this->adresse_facturation = $adresseFacturation;
    
        return $this;
    }

    /**
     * Get adresseFacturation
     *
     * @return boolean
     */
    public function getAdresseFacturation()
    {
        return $this->adresse_facturation;
    }

    /**
     * Set entite
     *
     * @param string $entite
     *
     * @return Adresse
     */
    public function setEntite($entite)
    {
        $this->entite = $entite;
    
        return $this;
    }

    /**
     * Get entite
     *
     * @return string
     */
    public function getEntite()
    {
        return $this->entite;
    }

    /**
     * Set entiteId
     *
     * @param integer $entiteId
     *
     * @return Adresse
     */
    public function setEntiteId($entiteId)
    {
        $this->entite_id = $entiteId;
    
        return $this;
    }

    /**
     * Get entiteId
     *
     * @return integer
     */
    public function getEntiteId()
    {
        return $this->entite_id;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Adresse
     */
    public function setDateCreation($dateCreation)
    {
        $this->date_creation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    /**
     * Set auteur
     *
     * @param \Base\MainBundle\Entity\Utilisateur $auteur
     *
     * @return Adresse
     */
    public function setAuteur(\Base\MainBundle\Entity\Utilisateur $auteur = null)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return \Base\MainBundle\Entity\Utilisateur
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Adresse
     */
    public function setDateModification($dateModification)
    {
        $this->date_modification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->date_modification;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
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
     * Set localite
     *
     * @param string $localite
     *
     * @return Adresse
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;
    
        return $this;
    }

    /**
     * Get localite
     *
     * @return string
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Adresse
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set numeroRue
     *
     * @param string $numeroRue
     *
     * @return Adresse
     */
    public function setNumeroRue($numeroRue)
    {
        $this->numero_rue = $numeroRue;
    
        return $this;
    }

    /**
     * Get numeroRue
     *
     * @return string
     */
    public function getNumeroRue()
    {
        return $this->numero_rue;
    }

    /**
     * Set rue
     *
     * @param string $rue
     *
     * @return Adresse
     */
    public function setRue($rue)
    {
        $this->rue = $rue;
    
        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Adresse
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Adresse
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set codePays
     *
     * @param string $codePays
     *
     * @return Adresse
     */
    public function setCodePays($codePays)
    {
        $this->code_pays = $codePays;
    
        return $this;
    }

    /**
     * Get codePays
     *
     * @return string
     */
    public function getCodePays()
    {
        return $this->code_pays;
    }

    /**
     * Set departement
     *
     * @param string $departement
     *
     * @return Adresse
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;
    
        return $this;
    }

    /**
     * Get departement
     *
     * @return string
     */
    public function getDepartement()
    {
        return $this->departement;
    }
}