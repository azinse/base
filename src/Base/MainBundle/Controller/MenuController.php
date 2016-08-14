<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Menu;
use Base\MainBundle\Form\MenuType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menu controller.
 *
 */
class MenuController extends Controller
{
    /**
     * Lists all Menu entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('BaseMainBundle:Menu')->findAll();
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('menu/index.html.twig', array(
                'menus' => $menus,
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('menu/index.html.twig', array(
            'menus' => $menus,
        ));
    }

    /**
     * Creates a new Menu entity.
     *
     */
    public function newAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm('Base\MainBundle\Form\MenuType', $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $menu->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('menu/new.html.twig', array(
                'menu' => $menu,
                'form' => $form->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('menu/new.html.twig', array(
            'menu' => $menu,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Menu entity.
     *
     */
    public function showAction(Request $request, Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('menu/show.html.twig', array(
                'menu' => $menu,
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('menu/show.html.twig', array(
            'menu' => $menu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Menu entity.
     *
     */
    public function editAction(Request $request, Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        $editForm = $this->createForm('Base\MainBundle\Form\MenuType', $menu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $menu->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('menu_edit', array('id' => $menu->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('menu/edit.html.twig', array(
                'menu' => $menu,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('menu/edit.html.twig', array(
            'menu' => $menu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Menu entity.
     *
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $form = $this->createDeleteForm($menu);
        $form->handleRequest($request);
        
        /**********REQUETE AJAX*******************/
        if($request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();
            $response = new Response(json_encode(1));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        /*************FIN REQUETE AJAX*************/
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();
        }

        return $this->redirectToRoute('menu_index');
    }

    /**
     * Creates a form to delete a Menu entity.
     *
     * @param Menu $menu The Menu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Menu $menu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('menu_delete', array('id' => $menu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
