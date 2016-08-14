<?php

namespace Base\MainBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * LoginListener: Class permettant de détecter la connexion des utilisateurs
 *
 * @author Zinsè
 */
class LoginListener {
	/** @var \Symfony\Component\Security\Core\SecurityContext */
	protected $tokenStorage;//remplace securityContext
        
        protected $authorizationChecker;
	
	/** @var \Doctrine\ORM\EntityManager */
	private $em;
	
        private $baseUrl;
        
        private $container;
        
        private $router;
        /**
	 * Constructor
	 * 
	 * @param SecurityContext $securityContext
	 * @param Doctrine        $doctrine
	 */
	public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, Doctrine $doctrine, Router $router, ContainerInterface $container)
	{
		$this->tokenStorage = $tokenStorage;
                $this->authorizationChecker = $authorizationChecker;
		$this->em              = $doctrine->getManager();
                $this->router = $router;
                $this->container = $container;
	}
	
	/**
	 * Detection de la connexion.
	 * 
	 * @param InteractiveLoginEvent $event
	 */
	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		if (true === $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                    $session = $event->getRequest()->getSession();
                    $session->set('base_url', $this->container->getParameter('base_url'));
                    $session->set('env', $this->container->get( 'kernel' )->getEnvironment());
                    // L'utilisateur vient juste de se connecter
                    $user = $event->getAuthenticationToken()->getUser();
                    //On récupère les adresses email pour lesquels l'utilisateur pourra attribuer une signature
                    $user_id = $user->getId();
                    $query = $this->em->createQuery("select distinct p.email, u.email from BaseMainBundle:Personne p, BaseMainBundle:Utilisateur u where p.auteur = u.id and u.id = $user_id");
                    $emails = $query->getResult();
                    $options = array();
                    foreach($emails as $m){
                        $val = $m['email'];
                        $options[$val]= $val;
                    }
                    $session->set('user_emails', $options);
                    date_default_timezone_set('Europe/Brussels'); //définir le fuseau horraire
                    $date = new \DateTime('now');
//                    $interval = new \DateInterval('P1M');
//                    $cloneDate=clone $date;
//                    $lastMonth=$cloneDate->sub($interval);
//                        //requête pour trouver les contrats de travails en cours ou terminés il y a moins de un mois de l'utilisateur (personne) courant
//                    $cwQuery = $this->em->createQuery(
//                    'SELECT c
//                    FROM TrefleRhBundle:Contratdetravails c
//                    WHERE (c.datefin IS NULL OR c.datefin >=:lastmonth)
//                    AND c.idpersonne=:idpersonne
//                    '
//                    )->setParameters(array(
//                     'lastmonth' => $lastMonth->format("Y-m-d"),
//                     'idpersonne'=>$user->getIdpersonne()->getId(),
//                    ));
//                    $cws=$cwQuery->getResult();
//                        //requête pour trouver les contrats de clients en cours ou terminés il y a moins de un mois de l'utilisateur (personne) courant s'il est un chez un sous-traitant
//                    $ctQuery = $this->em->createQuery(
//                    'SELECT ct
//                    FROM TrefleContratBundle:Contratressources ct
//                    WHERE (ct.datefinmission IS NULL OR ct.datefinmission >=:lastmonth)
//                    AND ct.idpersonne=:idpersonne
//                    '
//                    )->setParameters(array(
//                     'lastmonth' => $lastMonth->format("Y-m-d"),
//                     'idpersonne'=>$user->getIdpersonne()->getId(),
//                    ));
//                    $cts=$ctQuery->getResult();
//                       //requête pour savoir si l'utilisateur est un Administrateur auquel cas on évite de désactiver son compte
//                    $adminQuery = $this->em->createQuery(
//                    "SELECT a
//                    FROM TrefleParametresBundle:Appartenir a,TrefleParametresBundle:Groupes g
//                    WHERE a.idgroupe=g.id
//                    AND a.idutilisateur=:idutilisateur
//                    AND g.libellegroupe=:libelle
//                    "
//                    )->setParameters(array(
//                     'libelle' => 'Administrateur',
//                     'idutilisateur'=>$user->getId(),
//                    ));
//                    $admin=$adminQuery->getResult();
//                        //Si l'utilisateur n'a ni contrat de travail, ni contrats client (chez un sous-traitant) et n'est pas administrateur, on désactive son compte
//                    if(count($cws)<=0 && count($cts)<=0 && count($admin)<=0){
//                        $user->setActif(false);
//                        $this->em->persist($user);
//                        $this->em->flush();
//                            // Rediriger à la page précédente
//			$referer_url = $event->getRequest()->headers->get('referer');
//			$response = new RedirectResponse($referer_url);
//                        return $response;
//                    }
                    /*************************************************************Fin connexion précédente si nécessaire***************************/
                    $connexions = $this->em->getRepository('BaseMainBundle:Connexion')->findBy(array('auteur'=>$user->getId()),array('date_debut' => 'DESC'));
                    $derniereConnexion = null;
                    if(count($connexions) > 0){
                        $derniereConnexion = $connexions[0];
                        if( $derniereConnexion != null && $derniereConnexion->getDateFin() == null ){
                            
                            $derniereConnexion->setDateFin($date);
                            $this->em->persist($derniereConnexion);
                            $this->em->flush();
                        }
                    }
                    /*************************************************************Fin**************************************************************/
                    //$ipconnexion=$event->getRequest()->getClientIp();
                    //$ip = $this->request->server->get('REMOTE_ADDR');
                    $ipconnexion = $this->get_client_ip();
                    $connexion = new \Base\MainBundle\Entity\Connexion();
                    $connexion->setDateDebut($date);
                    $connexion->setAuteur($user);
                    $connexion->setIpConnexion($ipconnexion);
                    $connexion->setSessionId($event->getRequest()->getSession()->getId());
                    $this->em->persist($connexion);
                    $this->em->flush();
		}
		
		/*if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			// user has logged in using remember_me cookie
		}*/
		// do some other magic here
		//$user = $event->getAuthenticationToken()->getUser();
		
	}
        
        function get_client_ip() {
            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }
        
}

?>
