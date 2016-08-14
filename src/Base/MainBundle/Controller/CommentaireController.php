<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Commentaire;
use Base\MainBundle\Form\CommentaireType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Commentaire controller.
 *
 */
class CommentaireController extends Controller
{
    /**
     * Lists all Commentaire entities.
     *
     */
    public function indexAction(Request $request, $_route)
    {
        $em = $this->getDoctrine()->getManager();
        
        if($_route == "commentaire_index"){
            $commentaires = $em->getRepository('BaseMainBundle:Commentaire')->findBy(array('auteur'=> $this->getUser()->getId()), array('date_creation'=>'DESC'));
        }else{
            $commentaires = $em->getRepository('BaseMainBundle:Commentaire')->findBy(array('auteur'=> $this->getUser()->getId(), 'entite' => null), array('date_creation'=>'DESC'));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('commentaire/index.html.twig', array(
                'commentaires' => $commentaires,
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('commentaire/index.html.twig', array(
            'commentaires' => $commentaires,
        ));
    }

    /**
     * Creates a new Commentaire entity.
     *
     */
    public function newAction(Request $request)
    {
        $commentaire = new Commentaire();
        $form = $this->createForm('Base\MainBundle\Form\CommentaireType', $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $commentaire->setAuteur($this->getUser());
            if($request->get('entite')!=null && $request->get('entiteid')){
                $commentaire->setEntite($request->get('entite'));
                $commentaire->setEntiteId($request->get('entiteid'));
            }
            $em->persist($commentaire);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $commentaire->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('commentaire_show', array('id' => $commentaire->getId()));
        }
        
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('commentaire/new.html.twig', array(
                'commentaire' => $commentaire,
                'form' => $form->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('commentaire/new.html.twig', array(
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Commentaire entity.
     *
     */
    public function showAction(Request $request, Commentaire $commentaire)
    {
        $deleteForm = $this->createDeleteForm($commentaire);
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('commentaire/show.html.twig', array(
                'commentaire' => $commentaire,
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('commentaire/show.html.twig', array(
            'commentaire' => $commentaire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Commentaire entity.
     *
     */
    public function editAction(Request $request, Commentaire $commentaire)
    {
        $deleteForm = $this->createDeleteForm($commentaire);
        $editForm = $this->createForm('Base\MainBundle\Form\CommentaireType', $commentaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $commentaire->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('commentaire_edit', array('id' => $commentaire->getId()));
        }
        
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('commentaire/edit.html.twig', array(
                'commentaire' => $commentaire,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('commentaire/edit.html.twig', array(
            'commentaire' => $commentaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Commentaire entity.
     *
     */
    public function deleteAction(Request $request, Commentaire $commentaire)
    {
        $form = $this->createDeleteForm($commentaire);
        $form->handleRequest($request);
        /**********REQUETE AJAX*******************/
        if($request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentaire);
            $em->flush();
            $response = new Response(json_encode(1));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        /*************FIN REQUETE AJAX*************/
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentaire);
            $em->flush();
        }

        return $this->redirectToRoute('commentaire_index');
    }

    /**
     * Creates a form to delete a Commentaire entity.
     *
     * @param Commentaire $commentaire The Commentaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commentaire $commentaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentaire_delete', array('id' => $commentaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
