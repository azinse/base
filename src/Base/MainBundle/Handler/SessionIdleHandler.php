<?php
namespace Base\MainBundle\Handler;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+

/**
 * Permet de fermer la connexion après une durée définie
 *
 * @author developpement
 */
class SessionIdleHandler {

    protected $session;
    protected $tokenStorage; //remplace tokenStorage
    protected $authorizationChecker;
    protected $router;
    protected $maxIdleTime;
    protected $em;
    protected $translator;

    public function __construct(SessionInterface $session, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, RouterInterface $router, Doctrine $doctrine, $maxIdleTime = 0, TranslatorInterface $translator)
    {
        $this->session = $session; //On définit la session courante
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
        $this->maxIdleTime = $maxIdleTime;
        $this->em = $doctrine->getManager();
        $this->translator = $translator;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->session->start();
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
             //On enregistre l'url d'où vient la requete
            $previousUrl=$event->getRequest()->server->get('HTTP_REFERER');
            $explodes=  explode("/", $previousUrl);
            $postAction=0;
            foreach($explodes as $explode){
                if($explode=="create" || $explode=="update"){
                    $postAction+=1;
                    break;
                }
            }
                //Si il y a un url de retour enregistré et qu'il est différent de l'url d'arrivée (Referer) non post (create ou update)
                //On modifie la variable de session previousUrl
            $base  = $this->session->get("base_url");
            //var_dump($previousUrl);
            if($this->session->get('previousUrl')!=null){
                
                if($this->session->get('previousUrl')!==$previousUrl && $postAction==0){

                    if($this->session->get('previousUrl')!==$this->session->get('expreviousUrl')){
                        $this->session->set('expreviousUrl', $this->session->get('previousUrl'));
                    }
                    $this->session->set('previousUrl', $previousUrl);
                }
            }else{
                    $this->session->set('previousUrl', $previousUrl) ;
            }
            return;
        }
        
        //Si inactivité et temps max d'inactivité supérieur à zero
        if ($this->maxIdleTime > 0) {

            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();

            if ($lapse > $this->maxIdleTime && $this->tokenStorage->getToken()) {
                
                $user = $this->tokenStorage->getToken()->getUser();
                if($user instanceof \Base\MainBundle\Entity\Utilisateur){
                    $connexions = $this->em->getRepository('BaseMainBundle:Connexion')->findBy(array('auteur'=>$user->getId()),array('date_debut' => 'DESC'));
                    if(count($connexions) > 0){
                        $connexion = $connexions[0];
                        if($connexion!=null){
                            date_default_timezone_set('Europe/Brussels'); //définir le fuseau horraire
                            $date = new \DateTime('now');
                            $connexion->setDateFin($date);
                            $this->em->persist($connexion);
                            $this->em->flush();
                        }
                    }
                    $locale= $event->getRequest()->getLocale();
                    $translator = $this->translator;
                    $this->tokenStorage->setToken(null);
                    $this->session->getFlashBag()->add('deconnexionauto', $translator->trans("deconnexion_auto"));
                    $this->session->remove("previousUrl");
                    $this->session->remove("expreviousUrl");
                    // Change the route if you are not using FOSUserBundle.
                    $event->setResponse(new RedirectResponse($this->router->generate('login')));
                }
                
            }else if($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser()!=null && $this->tokenStorage->getToken()->getUser()!=''){
                $user = $this->tokenStorage->getToken()->getUser();
                if($user instanceof \Base\MainBundle\Entity\Utilisateur){
                    $connexions=$this->em->getRepository('BaseMainBundle:Connexion')->findOneBy(array('auteur' => $user->getId(),'session_id' => $this->session->getId()));
                    //Si l'utilisateur se connecte sur un autre ordinateur et donc que la date de fin de sa dernière connexion est définie,
                    //Alors on déconnecte l'utilisateur sur l'autre ordinateur
                    if ($connexions != null && $connexions->getDateFin()!=null) {
                            $locale= $event->getRequest()->getLocale();
                            $translator= $this->translator;
                            $this->tokenStorage->setToken(null);
                            $this->session->getFlashBag()->set('deconnexionauto', $translator->trans("compte_utilise"));
                            $this->session->remove("previousUrl");
                            $this->session->remove("expreviousUrl");
                            //$this->session->invalidate();
                            // Change the route if you are not using FOSUserBundle.
                            $event->setResponse(new RedirectResponse($this->router->generate('login')));
                    }
                }else{
                    $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_logout')));
                }
            }
        }
    }
}

?>
