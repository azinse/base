<?php

namespace Base\MainBundle\Handler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Routing\Router;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler {
    
    private $em;

    private $router;
    
    public function __construct( HttpUtils $httpUtils, array $options, Doctrine $doctrine, Router $router ) {
        parent::__construct( $httpUtils, $options );
        $this->em              = $doctrine->getManager();
        $this->router = $router;
        
    }

    public function onAuthenticationSuccess( Request $request, TokenInterface $token ) {

        $user = $token->getUser();
        if(!$user->hasGroupe('utilisateur')){
            $groupe = $this->em->getRepository("BaseMainBundle:Groupe")->findOneBy(array('nom'=>'utilisateur'));
            if(count($groupe) > 0){
                $user->addGroupe($groupe);
        
                $this->em->persist($user);
                $this->em->flush();
            }
        }
        if($user->getPersonneId()===NULL){
            $personne = new \Base\MainBundle\Entity\Personne();
            $personne->setNom($user->getUsername());
            $personne->setPrenom($user->getUsername());
            $personne->setAuteur($user);
            $personne->setEmail($user->getEmail());
            
            $this->em->persist($personne);
            $this->em->flush();
            
            $user->setPersonneId($personne);
        
            $this->em->persist($user);
            $this->em->flush();
        }
//        if($this->isRh($user)!=0){
//            
//            $sousmenu = $this->em->getRepository('TrefleParametresBundle:Sousmenus')->findOneBy(array("uri" => "salarie"));
//            if($sousmenu){
//                $session = $request->getSession();
//                $session->set('menu', $sousmenu->getIdmenu()->getId());
//                $session->set('menu_couleur', $sousmenu->getIdmenu()->getCouleur());
//                $session->set('sousmenu', $sousmenu->getId());
//            }
//            return new RedirectResponse($this->router->generate("salarie"));
//        }
        $response = parent::onAuthenticationSuccess( $request, $token );
        
        return $response;
    }
    /*************Récupérer les groupes******************/
//    public function isRh($user=null){
//            
//        if($user!=null){ 
//            $idUtilisateur = $user; 
//        }else{
//            $idUtilisateur=$this->get('security.context')->getToken()->getUser();
//        }
//        $em= $this->em;
//         //On récupère les groupes auxquels appartient l'utilisateur
//        $groupes=$em->getRepository('TrefleParametresBundle:Appartenir')->findByidutilisateur($idUtilisateur->getId());
//        $isRh=0;
//        foreach($groupes as $groupe){
//           if($groupe->getIdgroupe()->getLibellegroupe()==='Rh'){
//              $isRh+=1;
//            }
//        }
//         return $isRh;
//    }
    
}

?>
