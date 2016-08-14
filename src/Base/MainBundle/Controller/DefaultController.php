<?php

namespace Base\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints as EmailConstraint;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BaseMainBundle:Default:index.html.twig');
    }
    
    public function agendaAction()
    {
        return $this->render('BaseMainBundle:Default:agenda.html.twig');
    }

    /**
     * Permet de contruire le header du layout
     * @return twig template
     */
    public function headerAction()
    {
        return $this->render('BaseMainBundle:Default:header.html.twig');
    }
    /**
     *Permet de construire le menu
     * @return twig template
     */
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        //Selection de menu : 2 niveaux
        $menus = array();
        $parent = array();
        $groupeMenus = array();
        foreach($this->getUser()->getGroupe() as $groupe){
            foreach($groupe->getMenu() as $m){
                if($m->getActif()){
                //On enregistre l'id du menu parent
                if(!in_array($m->getParent()->getId(), $parent)){
                    $parent[] = $m->getParent()->getId();
                }
                //On enregistre la liste des menus du groupe
                if(!in_array($m->getId(), $groupeMenus)){
                    $groupeMenus[] = $m->getId();
                }
                }
            }
        }
        
        if(count($parent)> 0){
            //On récupère tous les menus de niveau 1
            $query = $em->createQuery("SELECT DISTINCT m from BaseMainBundle:Menu m where m.parent IS NULL and m.id IN (".implode(',',$parent).") order by m.position ASC, m.nom ASC");
            $parentMenus = $query->getResult();
            foreach ($parentMenus as $pm){
                $menus[$pm->getId()]['parent'] = $pm;
                $menus[$pm->getId()]['enfant'] = $this->getSmenu($pm, $groupeMenus);
            }
        }
        return $this->render('BaseMainBundle:Default:menu.html.twig',array(
            'menus' => $menus,
        ));
    }
    public function getSmenu($m, $groupemenu){
        $em = $this->getDoctrine()->getManager();
        //On récupère tous les sous menus d'un menu
        $id = $m->getId();
        $query = $em->createQuery("select distinct m from BaseMainBundle:Menu m where m.parent = $id and m.id IN (".implode(',',$groupemenu).") order by m.position ASC, m.nom ASC");
        $smenu = $query->getResult();
        
        return $smenu;

    }

    /**
     * Permet d'enregistrer en session les paramètres clients
     * @param Request $request
     * @return \Base\MainBundle\Controller\Response
     */
    public function paramAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $session = $request->getSession();
            if($request->get('theme')!==null){
                $session->set("theme",$request->get('theme'));
                $em = $this->getDoctrine()->getManager();
                $utilisateur = $this->getDoctrine()->getRepository("BaseMainBundle:Utilisateur")->find($this->getUser()->getId());
                $utilisateur->setTheme($request->get('theme'));
                $em->persist($utilisateur);
                $em->flush();
                
                $response = new Response(json_encode($request->get('theme')));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            
            $response = new Response(json_encode($session->get("theme")));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        }
        return $this->render('BaseMainBundle:Default:index.html.twig');
    }
    /**
     * 
     * @param array['sujet','expediteur', 'destinataire','cc', 'bcc'] 
     * 
     * 
     */
    public function sendEmailAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $from = $request->request->get("from");
            //$from = 'atenoukon@tango.lu';
//            $from = 'zinse.atenoukon@gmail.com';
            if($from === ""){
                $from = $this->getUser()->getEmail();
            }
            $to  =  $this->emailCheck($request->request->get("to"));
            if(count($to) <= 0){
                $response = new Response(json_encode($to[0]));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            $cc  =  $this->emailCheck($request->request->get("cc"));
            $cci =  $this->emailCheck($request->request->get("cci"));
            $body = $request->request->get("message");
            $objet = $request->request->get("objet");
            $message = \Swift_Message::newInstance(null)
                ->setSubject($objet)
                ->setFrom($from)
                ->setTo($to)
                ->setSender($from)
                ->setReplyTo($from)
                ->setBcc($cci)
                ->setBody("<html><head></head><body>$body</body></html>", "text/html");
            ;
            //Si l'utilisateur veut une copie de l'email
            if($request->request->get('copie')){
                $message->setCc($cc[]=$from);
            }else{
                $message->setCc($cc);
            }
            $files = array();
            if( count($request->files) > 0){
                //Les pjs
                $dossier = $this->container->getParameter('upload_directory').'/email';
                if ( ! is_dir($dossier)) {
                    mkdir($dossier, 0777, true);
                }
                $extensions = explode(',.',"gif,.jpg,.jpeg,.png,.bmp,.tif,.txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.odt,.ods,.odp,.csv");
                foreach($request->files->get('file') as $file){
                    //Si l'extension du fichier n'est pas parmi les extensions autorisées
                    if (in_array(strtolower($file->guessExtension()), $extensions)){
                            $fileName = $file->getClientOriginalName();
                            // Déplacement du fichier dans le dossier du serveur
                            if(!move_uploaded_file($file, "$dossier/$fileName")){
                                //Si le déplacement se passe mal
                                $erreur = $this->get('translator')->trans('size_directory_error'). ' '.$file->getClientOriginalName();
                                $response = new Response(json_encode($erreur));
                                $response->headers->set('Content-Type', 'application/json');

                                return $response;
                            }
                            //Le chemin du fichier sur le serveur
                            $url = $dossier . '/' . $fileName;
                            //Si le fichier est bien enregistré
                            if(file_exists($url)){
                                $message->attach(\Swift_Attachment::fromPath($url)->setFilename($fileName));
                                $files[]=$url;
                            }
                    }else{ 
                        $erreur = $this->get('translator')->trans('extention_error');

                        $response = new Response(json_encode($erreur));
                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
                    }
                }
                
            }
            $mailer = $this->get('mailer');
            $result = false;
            if( $mailer->send($message)){
                $result = true;
                $transport = $this->container->get('mailer')->getTransport();  
                $spool = $transport->getSpool();
                $spool->flushQueue($this->container->get('swiftmailer.transport.real'));
                foreach ($files as $f){
                    unlink($f);
                }        
            }
            
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        
         return $this->render('BaseMainBundle:Default:index.html.twig');
    }
    
    /**
     * Permet d'enregistrer en session les paramètres clients
     * @param Request $request
     * @return \Base\MainBundle\Controller\Response
     */
    public function newEmailAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            
            $response = new Response($this->render('default/email.html.twig', array(
            ))->getContent());

            return $response;
        }
        return $this->render('BaseMainBundle:Default:email.html.twig');
    }
    /**
     * Fonction permettant de vérifier les adresses email
     * @param string $des
     * @return array desfinal
     */
    public function emailCheck($des){
        $emailConstraint = new EmailConstraint\Email();
        $emailConstraint->message = $this->get('translator')->trans("Invalid email address");
        $desfinal = array();
        if( strpos($des,',') !== false || strpos($des,';') !== false) {
            $des =  str_replace(';', ',', $des);
            $des = trim($des);
            $desLists=  explode(',', $des);
            foreach($desLists as $desList){
                if (filter_var($desList, FILTER_VALIDATE_EMAIL)){
                    $desfinal[] = $desList; 
                }
            }
        }else{
            if (filter_var($des, FILTER_VALIDATE_EMAIL)){
                $desfinal[] = $des; 
            }
            
        }
        return $desfinal;
    }
}
