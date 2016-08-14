<?php

namespace Base\MainBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Base\MainBundle\Entity\Societe
 *
 * @ORM\Table(name="societe")
 * @ORM\Entity
 * @UniqueEntity(fields="nom", message="Ce nom de société est déjà utilisé")
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\SocieteRepository")
 */
class Societe
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string $type
     * 
     * @ORM\Column(name="type", type="string", length=25, nullable=false)
     */
    private $type;
    
    /**
     * @var string $nom
     * @Assert\NotBlank(message="ce champ doit être rempli")
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;
    
    /**
     * @var string $presentation
     *
     * @ORM\Column(name="presentation", type="text", nullable=true)
     */
    private $presentation;
    /**
     * @var string $numero_identification
     *
     * @ORM\Column(name="numero_identification", type="string", length=50, nullable=true)
     */
    private $numero_identification;
    
    /**
     * @var string
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=55, nullable=true)
     */
    private $email;
    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=35, nullable=true)
     */
    private $telephone;

    /**
     * @var string $fax
     *
     * @ORM\Column(name="fax", type="string", length=35, nullable=true)
     */
    private $fax;
    
    /**
     * @var string $site
     *
     * @ORM\Column(name="site", type="string", length=255, nullable=true)
     */
    private $site;
    
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;
    
    /**
     * @var string $couleur
     * 
     * @ORM\Column(name="couleur", type="string", length=7, nullable=true)
     */
    private $couleur;

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
     * @var Secteur
     *
     * @ORM\ManyToOne(targetEntity="Secteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="secteur_id", referencedColumnName="id")
     * })
     */
    private $secteur;
    
    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     * })
     */
    private $auteur;
    
    /**
     * @var string $logo
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    public function __construct() {
        // On initialiser les date de création et modification
        $this->setDateCreation(new \DateTime());
        if ($this->getDateModification() == null) {
            $this->setDateModification(new \DateTime());
        }
        $this->setActif(true);
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateDateModification() {
        // update the modified time
        $this->setDateModification(new \DateTime());
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
     * Set type
     *
     * @param string $type
     *
     * @return Societe
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Societe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set numeroIdentification
     *
     * @param string $numeroIdentification
     *
     * @return Societe
     */
    public function setNumeroIdentification($numeroIdentification)
    {
        $this->numero_identification = $numeroIdentification;

        return $this;
    }

    /**
     * Get numeroIdentification
     *
     * @return string
     */
    public function getNumeroIdentification()
    {
        return $this->numero_identification;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Societe
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Societe
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return Societe
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Societe
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Societe
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     *
     * @return Societe
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Societe
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
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Societe
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
     * Set auteur
     *
     * @param \Base\MainBundle\Entity\Utilisateur $auteur
     *
     * @return Societe
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
     * 
     * @return nom
     */
     public function __toString()
    {
        return $this->getNom();
    }
    
    /**
     * 
     * @return menu
     */
    public function getNomType() {
        if($this->getType()=="SGE"){
            return "Gestion";
        }elseif($this->getType()=="SCL"){
            return "Client";
        }elseif($this->getType()=="SFO"){
            return "Fournisseur";
        }elseif($this->getType()=="SST"){
            return "Sous-traitant";
        }else{
            return "Autre";
        }
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Societe
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     *
     * @return Societe
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    
        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set secteur
     *
     * @param \Base\MainBundle\Entity\Secteur $secteur
     *
     * @return Societe
     */
    public function setSecteur(\Base\MainBundle\Entity\Secteur $secteur = null)
    {
        $this->secteur = $secteur;
    
        return $this;
    }

    /**
     * Get secteur
     *
     * @return \Base\MainBundle\Entity\Secteur
     */
    public function getSecteur()
    {
        return $this->secteur;
    }
}
