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
 * SocietePersonne: table d'association entre une société et ses personnes
 *@author zinse
 * 
 * @ORM\Table(name="personne_societe")
 * @ORM\Entity
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\PersonneSocieteRepository")
 */

class PersonneSociete {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Societe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="societe_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $societe_id;

    /**
     * @var Societesgestion
     *
     * @ORM\ManyToOne(targetEntity="Personne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="personne_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $personne_id;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $date_creation;
    
    public function __construct() {
        // On initialiser les date de création
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return SocietePersonne
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
     * Set societeId
     *
     * @param \Base\MainBundle\Entity\Societe $societeId
     *
     * @return PersonneSociete
     */
    public function setSocieteId(\Base\MainBundle\Entity\Societe $societeId = null)
    {
        $this->societe_id = $societeId;

        return $this;
    }

    /**
     * Get societeId
     *
     * @return \Base\MainBundle\Entity\Societe
     */
    public function getSocieteId()
    {
        return $this->societe_id;
    }

    /**
     * Set personneId
     *
     * @param \Base\MainBundle\Entity\Personne $personneId
     *
     * @return PersonneSociete
     */
    public function setPersonneId(\Base\MainBundle\Entity\Personne $personneId = null)
    {
        $this->personne_id = $personneId;

        return $this;
    }

    /**
     * Get personneId
     *
     * @return \Base\MainBundle\Entity\Personne
     */
    public function getPersonneId()
    {
        return $this->personne_id;
    }
}