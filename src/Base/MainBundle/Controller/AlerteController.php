<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Alerte;
use Base\MainBundle\Form\AlerteType;

/**
 * Alerte controller.
 *
 */
class AlerteController extends Controller
{
    /**
     * Lists all Alerte entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $alertes = $em->getRepository('BaseMainBundle:Alerte')->findAll();

        return $this->render('alerte/index.html.twig', array(
            'alertes' => $alertes,
        ));
    }

    /**
     * Creates a new Alerte entity.
     *
     */
    public function newAction(Request $request)
    {
        $alerte = new Alerte();
        $form = $this->createForm('Base\MainBundle\Form\AlerteType', $alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alerte);
            $em->flush();

            return $this->redirectToRoute('alerte_show', array('id' => $alerte->getId()));
        }

        return $this->render('alerte/new.html.twig', array(
            'alerte' => $alerte,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Alerte entity.
     *
     */
    public function showAction(Alerte $alerte)
    {
        $deleteForm = $this->createDeleteForm($alerte);

        return $this->render('alerte/show.html.twig', array(
            'alerte' => $alerte,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Alerte entity.
     *
     */
    public function editAction(Request $request, Alerte $alerte)
    {
        $deleteForm = $this->createDeleteForm($alerte);
        $editForm = $this->createForm('Base\MainBundle\Form\AlerteType', $alerte);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alerte);
            $em->flush();

            return $this->redirectToRoute('alerte_edit', array('id' => $alerte->getId()));
        }

        return $this->render('alerte/edit.html.twig', array(
            'alerte' => $alerte,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Alerte entity.
     *
     */
    public function deleteAction(Request $request, Alerte $alerte)
    {
        $form = $this->createDeleteForm($alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($alerte);
            $em->flush();
        }

        return $this->redirectToRoute('alerte_index');
    }

    /**
     * Creates a form to delete a Alerte entity.
     *
     * @param Alerte $alerte The Alerte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Alerte $alerte)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('alerte_delete', array('id' => $alerte->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
