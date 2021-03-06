<?php

namespace Base\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="groupe")
 * @UniqueEntity(fields="nom", message="Ce nom de groupe est déjà utilisé")
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\GroupeRepository")
 */
class Groupe 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
     protected $id;
     
     /**
     * @var string $nom
     * 
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;
     /**
     * @ORM\ManyToMany(targetEntity="Menu")
     * @ORM\JoinTable(name="groupe_menu",
     *      joinColumns={@ORM\JoinColumn(name="groupe_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="menu_id", referencedColumnName="id",onDelete="CASCADE")}
     *      )
     */
     protected $menu;


    public function __construct() {
        $this->menu = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * 
     * @return le nom 
     */
     public function __toString()
    {
        return $this->getNom();
    }

    /**
     * Add menu
     *
     * @param \Base\MainBundle\Entity\Menu $menu
     *
     * @return Groupe
     */
    public function addMenu(\Base\MainBundle\Entity\Menu $menu)
    {
        $this->menu[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param \Base\MainBundle\Entity\Menu $menu
     */
    public function removeMenu(\Base\MainBundle\Entity\Menu $menu)
    {
        $this->menu->removeElement($menu);
    }

    /**
     * Get menu
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenu()
    {
        return $this->menu;
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
     * @return Groupe
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
}