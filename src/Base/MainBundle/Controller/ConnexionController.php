<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Connexion;
use Base\MainBundle\Form\ConnexionType;

/**
 * Connexion controller.
 *
 */
class ConnexionController extends Controller
{
    /**
     * Lists all Connexion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $connexions = $em->getRepository('BaseMainBundle:Connexion')->findBy(array(),array("date_debut"=>"DESC"),100);

        return $this->render('connexion/index.html.twig', array(
            'connexions' => $connexions,
        ));
    }

    /**
     * Creates a new Connexion entity.
     *
     */
    public function newAction(Request $request)
    {
        $connexion = new Connexion();
        $form = $this->createForm('Base\MainBundle\Form\ConnexionType', $connexion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($connexion);
            $em->flush();

            return $this->redirectToRoute('connexion_show', array('id' => $connexion->getId()));
        }

        return $this->render('connexion/new.html.twig', array(
            'connexion' => $connexion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Connexion entity.
     *
     */
    public function showAction(Connexion $connexion)
    {
        $deleteForm = $this->createDeleteForm($connexion);

        return $this->render('connexion/show.html.twig', array(
            'connexion' => $connexion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Connexion entity.
     *
     */
    public function editAction(Request $request, Connexion $connexion)
    {
        $deleteForm = $this->createDeleteForm($connexion);
        $editForm = $this->createForm('Base\MainBundle\Form\ConnexionType', $connexion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($connexion);
            $em->flush();

            return $this->redirectToRoute('connexion_edit', array('id' => $connexion->getId()));
        }

        return $this->render('connexion/edit.html.twig', array(
            'connexion' => $connexion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Connexion entity.
     *
     */
    public function deleteAction(Request $request, Connexion $connexion)
    {
        $form = $this->createDeleteForm($connexion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($connexion);
            $em->flush();
        }

        return $this->redirectToRoute('connexion_index');
    }

    /**
     * Creates a form to delete a Connexion entity.
     *
     * @param Connexion $connexion The Connexion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Connexion $connexion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('connexion_delete', array('id' => $connexion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
