<?php
namespace Base\MainBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Base\MainBundle\Entity\Connexion
 * @author Zinsè
 * Class de l'entité connexion permettant de tracer les connexions d'utilisateur
 * @ORM\Table(name="connexion")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Base\MainBundle\Entity\ConnexionRepository")
 */
class Connexion {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $ip_connexion
     * 
     * @ORM\Column(name="ip_connexion", type="string",length=250, nullable=false)
     */
    private $ip_connexion;
    
    /**
     * @var string $session_id
     * 
     * @ORM\Column(name="session_id", type="string",length=250, nullable=true)
     */
    private $session_id;

    /**
     * @var \DateTime $date_debut
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=false)
     */
    private $date_debut;
    
     /**
     * @var \DateTime $date_fin
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $date_fin;
    
    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $auteur;


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
     * Set ipConnexion
     *
     * @param string $ipConnexion
     *
     * @return Connexion
     */
    public function setIpConnexion($ipConnexion)
    {
        $this->ip_connexion = $ipConnexion;

        return $this;
    }

    /**
     * Get ipConnexion
     *
     * @return string
     */
    public function getIpConnexion()
    {
        return $this->ip_connexion;
    }

    /**
     * Set sessionId
     *
     * @param string $sessionId
     *
     * @return Connexion
     */
    public function setSessionId($sessionId)
    {
        $this->session_id = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Connexion
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
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Connexion
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
     * Set auteur
     *
     * @param \Base\MainBundle\Entity\Utilisateur $auteur
     *
     * @return Connexion
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
