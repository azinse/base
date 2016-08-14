<?php
namespace Base\MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 * @UniqueEntity(fields="email", message="Cette adresse Email est déjà utilisée")
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur est déjà utilisé")
 * @HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\UtilisateurRepository")
 */
class Utilisateur extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email",
     *     checkMX = true
     * )
     */
    protected $email;
       
    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     * @Assert\Regex(pattern="/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z].*[a-z]).{8,50}$/")
     */
    protected $plainPassword;
    
    /**
     * @var Personne
     *
     * @ORM\ManyToOne(targetEntity="Groupe", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="groupe_principal", referencedColumnName="id",nullable=true)
     * })
     */
    private $groupe_principal;
    
    /**
     * @ORM\ManyToMany(targetEntity="Base\MainBundle\Entity\Groupe")
     * @ORM\JoinTable(name="utilisateur_groupe",
     *      joinColumns={@ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="groupe_id", referencedColumnName="id",onDelete="CASCADE")}
     * )
     */
    protected $groupe;
    
     /**
     * @ORM\ManyToMany(targetEntity="Societe")
     * @ORM\JoinTable(name="utilisateur_societe",
     *      joinColumns={@ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="societe_id", referencedColumnName="id",onDelete="CASCADE")}
     *      )
     */
    private $societe;
    
    /**
     * @var Personne
     *
     * @ORM\ManyToOne(targetEntity="Personne", cascade={"persist","merge"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="personne_id", referencedColumnName="id",nullable=true, onDelete="CASCADE")
     * })
     */
    private $personne_id;
  
    /**
     * @var string $theme
     *
     * @ORM\Column(name="theme", type="string", length=20, nullable=true)
     */
    private $theme;
    
    /**
     * @var string $avatar
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;
    
    public function __construct()
    {
        $this->setEnabled(true);
        parent::__construct();
        $this->societe = new ArrayCollection();
    }

    /**
     * Add groupe
     *
     * @param \Base\MainBundle\Entity\Groupe $groupe
     *
     * @return Utilisateur
     */
    public function addGroupe(\Base\MainBundle\Entity\Groupe $groupe)
    {
        $this->groupe[] = $groupe;

        return $this;
    }

    /**
     * Remove groupe
     *
     * @param \Base\MainBundle\Entity\Groupe $groupe
     */
    public function removeGroupe(\Base\MainBundle\Entity\Groupe $groupe)
    {
        $this->groupe->removeElement($groupe);
    }

    /**
     * Get groupe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupe()
    {
        return $this->groupe;
    }
    
    /**
     * Has groupe
     *
     * @param string $groupe
     * @return boolean
     */
    public function hasGroupe($groupe)
    {
        return in_array(strtolower($groupe), array_map('strtolower', $this->getGroupe()->toArray()));
        //return in_array($groupe, $this->getGroupe()->toArray());
    }
    /**
     * Set idpersonne
     *
     * @param \Base\MainBundle\Entity\Personne $personne_id
     *
     * @return Utilisateur
     */
    public function setPersonneId(\Base\MainBundle\Entity\Personne $personne_id = null)
    {
        $this->personne_id = $personne_id;

        return $this;
    }

    /**
     * Get personne_id
     *
     * @return \Base\MainBundle\Entity\Personne
     */
    public function getPersonneId()
    {
        return $this->personne_id;
    }
    
    /**
     * 
     * @return username
     */
     public function __toString()
    {
        if($this->getUsername()!=null){
            return $this->getUsername();
        }else{
            return $this->getEmail();
        }
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    
    /*public function updateRoles() {
        
        $roles = $this->getRoles();
        foreach($this->getGroupe() as $groupe){
            foreach($groupe->getMenu() as $menu){
                if(!in_array($menu, $roles)){
                    $roles[] = $menu;
                }
            }
        }
        $this->setRoles($roles);
    }*/

    /**
     * Add societe
     *
     * @param \Base\MainBundle\Entity\Societe $societe
     *
     * @return Utilisateur
     */
    public function addSociete(\Base\MainBundle\Entity\Societe $societe)
    {
        $this->societe[] = $societe;

        return $this;
    }

    /**
     * Remove societe
     *
     * @param \Base\MainBundle\Entity\Societe $societe
     */
    public function removeSociete(\Base\MainBundle\Entity\Societe $societe)
    {
        $this->societe->removeElement($societe);
    }

    /**
     * Get societe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set groupePrincipal
     *
     * @param \Base\MainBundle\Entity\Groupe $groupePrincipal
     *
     * @return Utilisateur
     */
    public function setGroupePrincipal(\Base\MainBundle\Entity\Groupe $groupePrincipal = null)
    {
        $this->groupe_principal = $groupePrincipal;
    
        return $this;
    }

    /**
     * Get groupePrincipal
     *
     * @return \Base\MainBundle\Entity\Groupe
     */
    public function getGroupePrincipal()
    {
        return $this->groupe_principal;
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
        $this->enabled = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean
     */
    public function getActif()
    {
        return $this->enabled;
    }
    /* 
     * Exemple de regex: mot de passe d'au moins 8 caractères dont 2 majuscules, un caractère spécial, 3 minuscules, 2 chiffres
        ^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8}$
        ^                         Start anchor
        (?=.*[A-Z].*[A-Z])        Ensure string has two uppercase letters.
        (?=.*[!@#$&*])            Ensure string has one special case letter.
        (?=.*[0-9].*[0-9])        Ensure string has two digits.
        (?=.*[a-z].*[a-z].*[a-z]) Ensure string has three lowercase letters.
        .{8}                      Ensure string is of length 8.
        $                         End anchor. 
     */


    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return Utilisateur
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    
        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Utilisateur
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    
    /**
     * Get Nom
     * @return string 
     */
    public function getNom(){
        if($this->personne_id!=null){
            return $this->personne_id;
        }else{
            return $this->username;
        }
    }
}