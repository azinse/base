<?php
/*
 * @Zinsè ATENOUKON
 * 2016  * 
 */

namespace Base\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
/**
 * Personne: table des personnes
 *@author zinse
 * 
 * @ORM\Table(name="personne")
 * @UniqueEntity(fields="email", message="Cette adresse Email est déjà utilisée")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\PersonneRepository")
 */
class Personne
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $nom
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string $prenom
     * @Assert\NotBlank()
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    private $prenom;
    
    /**
     * @var string $sexe
     *
     * @ORM\Column(name="sexe", type="string", length=10, nullable=true)
     */
    private $sexe;
    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=35, nullable=true)
     */
    private $telephone;
    
    /**
     * @var string $mobile
     *
     * @ORM\Column(name="mobile", type="string", length=35, nullable=true)
     */
    private $mobile;
    
    /**
     * @var string
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=55, nullable=false)
     */
    private $email;
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;
    
    /**
     * @var \Date $date_naissance
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     */
    private $date_naissance;
    
    /**
     * @var boolean $contact
     *
     * @ORM\Column(name="contact", type="boolean", nullable=true)
     */
    private $contact;
    
    /**
     * @var string $fonction
     *
     * @ORM\Column(name="fonction", type="text",length=400, nullable=true)
     */
    private $fonction;
    
    /**
     * @var string $presentation
     *
     * @ORM\Column(name="presentation", type="text", length=400, nullable=true)
     */
    private $presentation;
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
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $auteur;
    
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
     * 
     * @return nom + prenom
     */
     public function __toString()
    {
        return $this->getNom().' '.$this->getPrenom();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Personne
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Personne
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Personne
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
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Personne
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }


    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Personne
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
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Personne
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->date_naissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    /**
     * Set contact
     *
     * @param boolean $contact
     *
     * @return Personne
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return boolean
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     *
     * @return Personne
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Personne
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
     * @return Personne
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
     * @return Personne
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
     * Set email
     *
     * @param string $email
     *
     * @return Personne
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
     * @return Personne
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
}