<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Personne;
use Base\MainBundle\Form\PersonneType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Personne controller.
 *
 */
class PersonneController extends Controller
{
    /**
     * Lists all Personne entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $personnes = $em->getRepository('BaseMainBundle:Personne')->findAll();
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('personne/index.html.twig', array(
                'personnes' => $personnes,
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('personne/index.html.twig', array(
            'personnes' => $personnes,
        ));
    }

    /**
     * Creates a new Personne entity.
     *
     */
    public function newAction(Request $request)
    {
        $personne = new Personne();
        $form = $this->createForm('Base\MainBundle\Form\PersonneType', $personne);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $personne->setAuteur($this->getUser());
            if($request->get('entite')!=null && $request->get('entite')=="societe"){
                $personne->setContact(true);
            }
            $em->persist($personne);
            $em->flush();
            /***********Si c'est un contact chez une société**************/
            if($request->get('entite')!=null && $request->get('entite')=="societe"){
                $personneSociete = new \Base\MainBundle\Entity\PersonneSociete();
                $personneSociete->setPersonneId($personne);
                $societe = $em->getRepository("BaseMainBundle:Societe")->find($request->get('entiteid'));
                $personneSociete->setSocieteId($societe);
                $em->persist($personneSociete);
                $em->flush();
            }
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $personne->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('personne_show', array('id' => $personne->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('personne/new.html.twig', array(
                'personne' => $personne,
                'form' => $form->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('personne/new.html.twig', array(
            'personne' => $personne,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Personne entity.
     *
     */
    public function showAction(Request $request, Personne $personne)
    {
        $deleteForm = $this->createDeleteForm($personne);
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $adresses = $em->getRepository('BaseMainBundle:Adresse')->findBy(array('entite' => 'personne','entite_id' => $personne->getId()),array('date_creation' => 'DESC'),1);
        $utilisateur = $em->getRepository('BaseMainBundle:Utilisateur')->findOneBy(array('personne_id' => $personne->getId()));
        $commentaires = $em->getRepository("BaseMainBundle:Commentaire")->findBy(array('entite'=>'personne', 'entite_id'=> $personne->getId()), array('date_creation' => 'DESC'), 20);
        $limit = 10;
        $query = $em->createQuery(
            "SELECT e
            FROM BaseMainBundle:Evenement e
            WHERE e.entite = :entite
            AND e.entite_id = :entite_id
            AND e.date_debut >= :now
            ORDER by e.date_debut ASC"
            )->setParameters(array(
             'entite'       => 'personne',
             'entite_id'    => $personne->getId(),
             'now'          => $date
            ))->setMaxResults($limit);
        $fevenements = $query->getResult();
        $query = $em->createQuery(
            "SELECT e
            FROM BaseMainBundle:Evenement e
            WHERE e.entite = :entite
            AND e.entite_id = :entite_id
            AND e.date_debut < :now
            ORDER by e.date_debut DESC"
            )->setParameters(array(
             'entite'       => 'personne',
             'entite_id'    => $personne->getId(),
             'now'          => $date
            ))->setMaxResults($limit);
        $devenements = $query->getResult();
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('personne/show.html.twig', array(
                'personne' => $personne,
                'delete_form' => $deleteForm->createView(),
                'adresses'   => $adresses,
                'utilisateur' => $utilisateur,
                'commentaires' => $commentaires,
                'fevenements' => $fevenements,
                'devenements' => $devenements
                
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('personne/show.html.twig', array(
            'personne' => $personne,
            'delete_form' => $deleteForm->createView(),
            'adresses'   => $adresses,
            'utilisateur' => $utilisateur,
            'commentaires' => $commentaires,
            'fevenements' => $fevenements,
            'devenements' => $devenements
        ));
    }

    /**
     * Displays a form to edit an existing Personne entity.
     *
     */
    public function editAction(Request $request, Personne $personne)
    {
        $deleteForm = $this->createDeleteForm($personne);
        $editForm = $this->createForm('Base\MainBundle\Form\PersonneType', $personne);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $personne->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('personne_edit', array('id' => $personne->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('personne/edit.html.twig', array(
                'personne' => $personne,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('personne/edit.html.twig', array(
            'personne' => $personne,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Personne entity.
     *
     */
    public function deleteAction(Request $request, Personne $personne)
    {
        $form = $this->createDeleteForm($personne);
        $form->handleRequest($request);
        
        /**********REQUETE AJAX*******************/
        if($request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            $personne->setActif(false);
            $em->persist($personne);
            $em->flush();
            $response = new Response(json_encode(1));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        /*************FIN REQUETE AJAX*************/
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $personne->setActif(false);
            //$em->remove($personne);
            $em->flush();
        }

        return $this->redirectToRoute('personne_index');
    }

    /**
     * Creates a form to delete a Personne entity.
     *
     * @param Personne $personne The Personne entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Personne $personne)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('personne_delete', array('id' => $personne->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Redirige sur persone_show avec pour id l'id de la personne associée à l'utilisateur courant
     */
    public function profilAction(){
        
        return $this->redirectToRoute('personne_show', array('id' => $this->getUser()->getPersonneId()->getId()));
    }
}
