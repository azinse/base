<?php

namespace Base\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="secteur")
 * @UniqueEntity(fields="nom")
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\SecteurRepository")
 * 
 */
class Secteur {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $nom
     * 
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var menu
     *
     * @ORM\ManyToOne(targetEntity="Secteur")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    
    /**
     * 
     * @return menu
     */
    public function getNomParent() { 
        return $this->getParent() ? $this->getParent()->getNom() : "";         
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
     * @return Secteur
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
     * Set parent
     *
     * @param \Base\MainBundle\Entity\Secteur $parent
     *
     * @return Secteur
     */
    public function setParent(\Base\MainBundle\Entity\Secteur $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Base\MainBundle\Entity\Secteur
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * 
     * @return nom
     */
     public function __toString()
    {
         return $this->getNom();
    }

}