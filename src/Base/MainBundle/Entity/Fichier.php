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
 * Classe de gestion des pièces jointes
 *@author zinse
 * 
 * @ORM\Table(name="fichier")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\FichierRepository")
 */
class Fichier {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string $categorie
     * @ORM\Column(name="categorie", type="string", nullable=true)
     */
    private $categorie;
    
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
     * @var string $url
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;

    /**
     * @var string $taille
     * @ORM\Column(name="taille", type="string", nullable=true)
     */
    private $taille;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $date_debut;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_butoir", type="datetime", nullable=true)
     */
    private $date_butoir;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $date_creation;
   
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
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Fichier
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set entite
     *
     * @param string $entite
     *
     * @return Fichier
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
     * @return Fichier
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
     * Set url
     *
     * @param string $url
     *
     * @return Fichier
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
     * Set taille
     *
     * @param string $taille
     *
     * @return Fichier
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return string
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Fichier
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
     * @return Fichier
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Fichier
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
     * Set dateButoir
     *
     * @param \DateTime $dateButoir
     *
     * @return Fichier
     */
    public function setDateButoir($dateButoir)
    {
        $this->date_butoir = $dateButoir;
    
        return $this;
    }

    /**
     * Get dateButoir
     *
     * @return \DateTime
     */
    public function getDateButoir()
    {
        return $this->date_butoir;
    }
}