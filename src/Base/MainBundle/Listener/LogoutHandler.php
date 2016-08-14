<?php
namespace Base\MainBundle\Listener;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
//use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+
/**
 * Permet de gérer la déconnexion et d'enregistrer la date de fin de celle-ci
 *
 * @author developpement
 */
class LogoutHandler implements LogoutHandlerInterface {
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor
     * @param EntityManager $em
     */
    public function __construct(Doctrine $doctrine)
    {

        $this->em = $doctrine->getManager();
    }

    /**
     * Do post logout stuff
     */
    public function logout(Request $request, Response $response, TokenInterface $authToken)
    {
        
        $user = $authToken->getUser();
        if($user instanceof \Base\MainBundle\Entity\Utilisateur){
            $connexions = $this->em->getRepository('BaseMainBundle:Connexion')->findBy(array('auteur'=>$user->getId()),array('date_debut' => 'DESC'));
            $connexion=null;
            if(count($connexions) > 0){
                $connexion = $connexions[0];
            }
            if($connexion!=null){
                date_default_timezone_set('Europe/Brussels'); //définir le fuseau horraire
                $date = new \DateTime('now');
                $connexion->setDateFin($date);
                $this->em->persist($connexion);
                $this->em->flush();
            }
            $request->getSession()->remove("previousUrl");
            $request->getSession()->remove("expreviousUrl");
            //$request->getSession()->invalidate();
            $this->get('security.token_storage')->setToken(null);
            return $response;
        }
    }
}

?>
