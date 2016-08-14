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
 * Classe de gestion des commentaires
 *@author zinse
 * 
 * @ORM\Table(name="commentaire")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\CommentaireRepository")
 */
class Commentaire {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
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
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;
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
     * Set entite
     *
     * @param string $entite
     *
     * @return Commentaire
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
     * @return Commentaire
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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Commentaire
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
     * @return Commentaire
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
     * @return Commentaire
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
}