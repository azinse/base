<?php

namespace Base\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="menu")
 * @UniqueEntity(fields="nom", message="Ce nom de menu est déjà utilisé")
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\MenuRepository")
 * 
 */
class Menu {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $nom
     * 
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;
    
    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=100, nullable=true)
     */
    private $url;
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @var string $class
     *
     * @ORM\Column(name="class", type="string", length=20, nullable=true)
     */
    private $class;
    
    /**
     * @var integer $position
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;
    
    /**
     * @var string $couleur
     * 
     * @ORM\Column(name="couleur", type="string", length=7, nullable=true)
     */
    private $couleur;
    
    /**
     * @var menu
     *
     * @ORM\ManyToOne(targetEntity="Menu")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    

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
     * @return Menu
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
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Menu
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
     * Set class
     *
     * @param string $class
     *
     * @return Menu
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Menu
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     *
     * @return Menu
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
     * Set parent
     *
     * @param \Base\MainBundle\Entity\Menu $parent
     *
     * @return Menu
     */
    public function setParent(\Base\MainBundle\Entity\Menu $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Base\MainBundle\Entity\Menu
     */
    public function getParent()
    {
        return $this->parent;
//        if($this->getParent()==null){
//            return $this;
//        }else{
//            return $this->parent;
//        }
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
     * Set url
     *
     * @param string $url
     *
     * @return Menu
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
     * 
     * @return nomcomplet
     */
     public function getNomcomplet()
    {
         if($this->getParent()==null){
            return $this->getNom();
         }else{
             return $this->getParent()->getNomcomplet().'\\'.$this->getNom();
         }
    }
    /**
     * 
     * @return menu
     */
    public function getNomParent() { 
        return $this->getParent() ? $this->getParent()->getNom() : "";         
    }
    
}