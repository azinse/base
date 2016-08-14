<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Secteur;
use Base\MainBundle\Form\SecteurType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Secteur controller.
 *
 */
class SecteurController extends Controller
{
    /**
     * Lists all Secteur entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $secteurs = $em->getRepository('BaseMainBundle:Secteur')->findAll();
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('secteur/index.html.twig', array(
                'secteurs' => $secteurs,
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('secteur/index.html.twig', array(
            'secteurs' => $secteurs,
        ));
    }

    /**
     * Creates a new Secteur entity.
     *
     */
    public function newAction(Request $request)
    {
        $secteur = new Secteur();
        $form = $this->createForm('Base\MainBundle\Form\SecteurType', $secteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($secteur);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $secteur->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('secteur_show', array('id' => $secteur->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('secteur/new.html.twig', array(
                'secteur' => $secteur,
                'form' => $form->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('secteur/new.html.twig', array(
            'secteur' => $secteur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Secteur entity.
     *
     */
    public function showAction(Request $request, Secteur $secteur)
    {
        $deleteForm = $this->createDeleteForm($secteur);
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('secteur/show.html.twig', array(
            'secteur' => $secteur,
            'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('secteur/show.html.twig', array(
            'secteur' => $secteur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Secteur entity.
     *
     */
    public function editAction(Request $request, Secteur $secteur)
    {
        $deleteForm = $this->createDeleteForm($secteur);
        $editForm = $this->createForm('Base\MainBundle\Form\SecteurType', $secteur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($secteur);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $secteur->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('secteur_edit', array('id' => $secteur->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('secteur/edit.html.twig', array(
                'secteur' => $secteur,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('secteur/edit.html.twig', array(
            'secteur' => $secteur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Secteur entity.
     *
     */
    public function deleteAction(Request $request, Secteur $secteur)
    {
        $form = $this->createDeleteForm($secteur);
        $form->handleRequest($request);
        /**********REQUETE AJAX*******************/
        if($request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($secteur);
            $em->flush();
            $response = new Response(json_encode(1));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        /*************FIN REQUETE AJAX*************/
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($secteur);
            $em->flush();
        }

        return $this->redirectToRoute('secteur_index');
    }

    /**
     * Creates a form to delete a Secteur entity.
     *
     * @param Secteur $secteur The Secteur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Secteur $secteur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('secteur_delete', array('id' => $secteur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
