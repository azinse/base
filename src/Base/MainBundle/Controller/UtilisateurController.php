<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Base\MainBundle\Entity\Utilisateur;
use Base\MainBundle\Form\UtilisateurType;

/**
 * Utilisateur controller.
 *
 */
class UtilisateurController extends Controller
{
    /**
     * Lists all Utilisateur entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $utilisateurs = $em->getRepository('BaseMainBundle:Utilisateur')->findAll();
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('utilisateur/index.html.twig', array(
                'utilisateurs' => $utilisateurs,
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('utilisateur/index.html.twig', array(
            'utilisateurs' => $utilisateurs,
        ));
    }

    /**
     * Creates a new Utilisateur entity.
     *
     */
    public function newAction(Request $request)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm('Base\MainBundle\Form\UtilisateurType', $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $groupe = $em->getRepository("BaseMainBundle:Groupe")->findOneBy(array('nom'=>'utilisateur'));
            if(count($groupe) > 0){
                $utilisateur->addGroupe($groupe);
            }
            $em->persist($utilisateur);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $utilisateur->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('utilisateur_show', array('id' => $utilisateur->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('utilisateur/new.html.twig', array(
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('utilisateur/new.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Utilisateur entity.
     *
     */
    public function showAction(Request $request, Utilisateur $utilisateur)
    {
        $deleteForm = $this->createDeleteForm($utilisateur);
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('utilisateur/show.html.twig', array(
                'utilisateur' => $utilisateur,
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('utilisateur/show.html.twig', array(
            'utilisateur' => $utilisateur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Utilisateur entity.
     *
     */
    public function editAction(Request $request, Utilisateur $utilisateur)
    {
        $deleteForm = $this->createDeleteForm($utilisateur);
        $editForm = $this->createForm('Base\MainBundle\Form\UtilisateurType', $utilisateur);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $utilisateur->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*************************/
            return $this->redirectToRoute('utilisateur_show', array('id' => $utilisateur->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('utilisateur/edit.html.twig', array(
                'utilisateur' => $utilisateur,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Utilisateur entity.
     *
     */
    public function deleteAction(Request $request, Utilisateur $utilisateur)
    {
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);
        
        /**********REQUETE AJAX*******************/
        if($request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            $utilisateur->setEnabled(false);
            $em->persist($utilisateur);
            $em->flush();
            $response = new Response(json_encode(1));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        /*************FIN REQUETE AJAX*************/
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }

    /**
     * Creates a form to delete a Utilisateur entity.
     *
     * @param Utilisateur $utilisateur The Utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('utilisateur_delete', array('id' => $utilisateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
