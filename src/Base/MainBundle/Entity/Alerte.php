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
 * Alerte: table des alertes
 *@author zinse
 * 
 * @ORM\Table(name="alerte")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\AlerteRepository")
 */
class Alerte {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;
    
    /**
     * @var integer $utilisateur_id
     *
     * @ORM\Column(name="utilisateur_id", type="integer",nullable=false)
     */
    private $utilisateur_id;
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;
    
    /**
     * @var string $valeur_champ
     *
     * @ORM\Column(name="valeur_champ", type="string", nullable=true)
     */
    private $valeur_champ;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $date_modification;   

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
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Alerte
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
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Alerte
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
     * Set code
     *
     * @param string $code
     *
     * @return Alerte
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set utilisateurId
     *
     * @param integer $utilisateurId
     *
     * @return Alerte
     */
    public function setUtilisateurId($utilisateurId)
    {
        $this->utilisateur_id = $utilisateurId;

        return $this;
    }

    /**
     * Get utilisateurId
     *
     * @return integer
     */
    public function getUtilisateurId()
    {
        return $this->utilisateur_id;
    }

    /**
     * Set valeurChamp
     *
     * @param string $valeurChamp
     *
     * @return Alerte
     */
    public function setValeurChamp($valeurChamp)
    {
        $this->valeur_champ = $valeurChamp;

        return $this;
    }

    /**
     * Get valeurChamp
     *
     * @return string
     */
    public function getValeurChamp()
    {
        return $this->valeur_champ;
    }
}
