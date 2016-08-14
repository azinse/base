<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Societe;
use Base\MainBundle\Form\SocieteType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Societe controller.
 *
 */
class SocieteController extends Controller
{
    /**
     * Lists all Societe entities.
     *
     */
    public function indexAction(Request $request, $_route)
    {
        switch ($_route){
            case "client_index":
                $type = 'client';
                break;
            case "fournisseur_index":
                $type = 'fournisseur';
                break;
            case "structure_index":
                $type = 'structure';
                break;
            case "soustraitant_index":
                $type = 'soustraitant';
                break;
            case "societe_index":
                $type = '';
                break;
        }
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT s FROM BaseMainBundle:Societe s";
        if($type !=''){
            $sql.= " WHERE s.type = '$type'";
            if(!$this->getUser()->hasGroupe('administrateur')){
                $sql.= " AND s.utilisateur_id = ".$this->getUser();
            }
        }else{
            if(!$this->getUser()->hasGroupe('administrateur')){
                $sql.= " WHERE s.utilisateur_id = ".$this->getUser();
            }
        }
        $sql .= " ORDER BY s.nom ASC, s.type ASC";
        
        $query = $em->createQuery($sql);
        $societes = $query->getResult();
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('societe/index.html.twig', array(
                'societes' => $societes,
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('societe/index.html.twig', array(
            'societes' => $societes,
        ));
    }

    /**
     * Creates a new Societe entity.
     *
     */
    public function newAction(Request $request)
    {
        $societe = new Societe();
        $form = $this->createForm('Base\MainBundle\Form\SocieteType', $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $societe->setAuteur($this->getUser());
            if($request->get('type')!=null){
                $societe->setType($request->get('type'));
            }else{
                $societe->setType('client');
            }
            $em->persist($societe);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $societe->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('societe_show', array('id' => $societe->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('societe/new.html.twig', array(
                'societe' => $societe,
                'form' => $form->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('societe/new.html.twig', array(
            'societe' => $societe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Societe entity.
     *
     */
    public function showAction(Request $request, Societe $societe)
    {
        $deleteForm = $this->createDeleteForm($societe);
        $adresses = $this->getDoctrine()->getRepository('BaseMainBundle:Adresse')->findBy(array('entite' => 'societe','entite_id' => $societe->getId(),'adresse_facturation'=>false),array('date_creation' => 'DESC'));
        $adressef = $this->getDoctrine()->getRepository('BaseMainBundle:Adresse')->findOneBy(array('entite' => 'societe','entite_id' => $societe->getId(),'adresse_facturation'=>true));
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT p
            FROM BaseMainBundle:Personne p, BaseMainBundle:PersonneSociete ps
            WHERE p.id = ps.personne_id
            AND ps.societe_id = :societe_id
            AND p.actif = 1
            ORDER by p.nom ASC, p.prenom ASC"

            )->setParameters(array(
             'societe_id' => $societe->getId(),
            ));
        $contacts = $query->getResult();
        $commentaires = $em->getRepository("BaseMainBundle:Commentaire")->findBy(array('entite'=>'societe', 'entite_id'=> $societe->getId()), array('date_creation' => 'DESC'), 20);
        $date = new \DateTime('now');
        $limit = 10;
        $query = $em->createQuery(
            "SELECT e
            FROM BaseMainBundle:Evenement e
            WHERE e.entite = :entite
            AND e.entite_id = :entite_id
            AND e.date_debut >= :now
            ORDER by e.date_debut ASC"
            )->setParameters(array(
             'entite'       => 'societe',
             'entite_id'    => $societe->getId(),
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
             'entite'       => 'societe',
             'entite_id'    => $societe->getId(),
             'now'          => $date
            ))->setMaxResults($limit);
        $devenements = $query->getResult();
        $contactCommentaires = array();
        $contactEvenements = array();
        foreach ($contacts as $contact){
            $coms = $em->getRepository("BaseMainBundle:Commentaire")->findBy(array('entite'=>'personne', 'entite_id'=> $contact->getId()), array('date_creation' => 'DESC'),1);
            $contactCommentaires[$contact->getId()] = $coms;
            $evs = $em->getRepository("BaseMainBundle:Evenement")->findBy(array('entite'=>'personne', 'entite_id'=> $contact->getId()), array('date_debut' => 'DESC'),1);
            $contactEvenements[$contact->getId()] = $evs;
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('societe/show.html.twig', array(
                'societe' => $societe,
                'delete_form' => $deleteForm->createView(),
                'adresses'   => $adresses,
                'adressef'   => $adressef,
                'contacts'  => $contacts,
                'commentaires' => $commentaires,
                'fevenements' => $fevenements,
                'devenements' => $devenements,
                'contactcommentaires' => $contactCommentaires,
                'contactevenements'  =>  $contactEvenements,
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('societe/show.html.twig', array(
            'societe' => $societe,
            'delete_form' => $deleteForm->createView(),
            'adresses'   => $adresses,
            'adressef'   => $adressef,
            'contacts'  => $contacts,
            'commentaires' => $commentaires,
            'fevenements' => $fevenements,
            'devenements' => $devenements,
            'contactcommentaires' => $contactCommentaires,
            'contactevenements'  =>  $contactEvenements,
        ));
    }

    /**
     * Displays a form to edit an existing Societe entity.
     *
     */
    public function editAction(Request $request, Societe $societe)
    {
        $deleteForm = $this->createDeleteForm($societe);
        $editForm = $this->createForm('Base\MainBundle\Form\SocieteType', $societe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($societe);
            $em->flush();
            /************REQUETE AJAX****************/
            if($request->isXmlHttpRequest()){
                $response = new Response(json_encode(array("statut" => "ok","id" => $societe->getId())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            /***************FIN*******************************/
            return $this->redirectToRoute('societe_edit', array('id' => $societe->getId()));
        }
        /************REQUETE AJAX****************/
        if($request->isXmlHttpRequest()){
            $response = new Response($this->render('societe/edit.html.twig', array(
                'societe' => $societe,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ))->getContent());

            return $response;
        }
        /****************FIN********************/
        return $this->render('societe/edit.html.twig', array(
            'societe' => $societe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Societe entity.
     *
     */
    public function deleteAction(Request $request, Societe $societe)
    {
        $form = $this->createDeleteForm($societe);
        $form->handleRequest($request);
        /**********REQUETE AJAX*******************/
        if($request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            $societe->setActif(false);
            $em->persist($societe);
            $em->flush();
            $response = new Response(json_encode(1));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        /*************FIN REQUETE AJAX*************/
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($societe);
            $em->flush();
        }

        return $this->redirectToRoute('societe_index');
    }

    /**
     * Creates a form to delete a Societe entity.
     *
     * @param Societe $societe The Societe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Societe $societe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('societe_delete', array('id' => $societe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
