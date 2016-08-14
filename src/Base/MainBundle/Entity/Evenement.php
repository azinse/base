<?php

/*
 * @Zinsè ATENOUKON
 * 2016  *
 */
namespace Base\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Base\MainBundle\Component\Validator\Constraints as Assert2;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * Evenement: table des évènements 
 *@author zinse
 * 
 * @ORM\Table(name="evenement")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\EvenementRepository")
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=false)
     */
    private $date_debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $date_fin;

    /**
     * @var boolean $journee_entiere
     *
     * @ORM\Column(name="journee_entiere", type="boolean", nullable=true)
     */
    private $journee_entiere;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var string $titre
     * 
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;
    /**
     * @var string $lieu
     * 
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true)
     */
    private $lieu;
    
    /**
     * @var string $url
     * 
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;
    
    
    /**
     * @var string $couleur_fond
     * 
     * @ORM\Column(name="couleur_fond", type="string", length=25, nullable=true)
     */
    private $couleur_fond;
    
    /**
     * @var string $couleur_texte
     * 
     * @ORM\Column(name="couleur_texte", type="string", length=25, nullable=true)
     */
    private $couleur_texte;
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
     * @var boolean $inactif
     *
     * @ORM\Column(name="inactif", type="boolean", nullable=true)
     */
    private $inactif;
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
    
    /**
     * @ORM\ManyToMany(targetEntity="Utilisateur")
     * @ORM\JoinTable(name="evenement_utilisateur",
     *      joinColumns={@ORM\JoinColumn(name="evenement_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id",onDelete="CASCADE")}
     *      )
     */
     protected $invite;
     
    public function __construct() {
        // On initialiser les date de création et modification
        $this->setDateCreation(new \DateTime());
        if ($this->getDateModification() == null) {
            $this->setDateModification(new \DateTime());
        }
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Evenement
     */
    public function setDateDebut($dateDebut)
    {
        $this->date_debut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Evenement
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Evenement
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
     * @return Evenement
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
     * @return Evenement
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Evenement
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }
    /**
     * 
     * @return le titre 
     */
     public function __toString()
    {
        return $this->getTitre();
    }
    /**
     * Add invite
     *
     * @param \Base\MainBundle\Entity\Utilisateur $invite
     *
     * @return Evenement
     */
    public function addInvite(\Base\MainBundle\Entity\Utilisateur $invite)
    {
        $this->invite[] = $invite;
    
        return $this;
    }

    /**
     * Remove invite
     *
     * @param \Base\MainBundle\Entity\Utilisateur $invite
     */
    public function removeInvite(\Base\MainBundle\Entity\Utilisateur $invite)
    {
        $this->invite->removeElement($invite);
    }

    /**
     * Get invite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvite()
    {
        return $this->invite;
    }

    /**
     * Set entite
     *
     * @param string $entite
     *
     * @return Evenement
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
     * @return Evenement
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
     * Set inactif
     *
     * @param boolean $inactif
     *
     * @return Evenement
     */
    public function setInactif($inactif)
    {
        $this->inactif = $inactif;

        return $this;
    }

    /**
     * Get inactif
     *
     * @return boolean
     */
    public function getInactif()
    {
        return $this->inactif;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Evenement
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;
    
        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * Set journeeEntiere
     *
     * @param boolean $journeeEntiere
     *
     * @return Evenement
     */
    public function setJourneeEntiere($journeeEntiere)
    {
        $this->journee_entiere = $journeeEntiere;
    
        return $this;
    }

    /**
     * Get journeeEntiere
     *
     * @return boolean
     */
    public function getJourneeEntiere()
    {
        return $this->journee_entiere;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Evenement
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set couleurFond
     *
     * @param string $couleurFond
     *
     * @return Evenement
     */
    public function setCouleurFond($couleurFond)
    {
        $this->couleur_fond = $couleurFond;
    
        return $this;
    }

    /**
     * Get couleurFond
     *
     * @return string
     */
    public function getCouleurFond()
    {
        return $this->couleur_fond;
    }

    /**
     * Set couleurTexte
     *
     * @param string $couleurTexte
     *
     * @return Evenement
     */
    public function setCouleurTexte($couleurTexte)
    {
        $this->couleur_texte = $couleurTexte;
    
        return $this;
    }

    /**
     * Get couleurTexte
     *
     * @return string
     */
    public function getCouleurTexte()
    {
        return $this->couleur_texte;
    }
}