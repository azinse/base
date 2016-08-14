<?php

namespace Base\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Base\MainBundle\Entity\Fichier;
use Base\MainBundle\Form\FichierType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fichier controller.
 *
 */
class FichierController extends Controller
{
    /**
     * Lists all Fichier entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fichiers = $em->getRepository('BaseMainBundle:Fichier')->findAll();

        return $this->render('fichier/index.html.twig', array(
            'fichiers' => $fichiers,
        ));
    }

    /**
     * Creates a new Fichier entity.
     *
     */
    public function newAction(Request $request)
    {
        $fichier = new Fichier();
        if($request->isXmlHttpRequest()){
            $webPath = $request->getBasePath();
            $sitePath = $request->getSchemeAndHttpHost().$request->getBasePath();
            $date = new \DateTime('now');
            $annee = $date->format('Y');
            $dossier = $this->container->getParameter('upload_directory').'/'.$request->get("table").'/'.$annee;
            if($request->get("type")!='pj'){$dossier.='/'.$request->get("type");}
            if ( ! is_dir($dossier)) {
                mkdir($dossier, 0755, true);
            }
            $extensions = explode(',.',$request->get("extension"));
            $reponses = array();
            foreach($request->files->get('file') as $file){
                $fichier = new Fichier();
                //Si l'extension du fichier n'est pas parmi les extensions autorisées
                if (in_array(strtolower($file->guessExtension()), $extensions)){
                    // Changement du nom du fichier
                        $fileName = time().'_'. preg_replace("/[^a-z0-9\.]/", "_", strtolower($file->getClientOriginalName()));
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
                            
                            $fichier->setAuteur($this->getUser());
                            $fichier->setCategorie($request->get('type'));
                            $fichier->setEntite($request->get('table'));
                            $fichier->setEntiteId(intval($request->get('table_id')));
                            if($request->get('date_debut')!=null){
                               $fichier->setDateDebut(new \DateTime($request->get('date_debut'))); 
                            }
                            if($request->get('date_butoir')!=null){
                               $fichier->setDateButoir(new \DateTime($request->get('date_butoir'))); 
                            }
                            $fichier->setUrl($url);
                            $fichier->setTaille($this->formatSizeUnits(filesize($file)));
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($fichier);
                            $em->flush();
                            if($request->get('table')==="utilisateur" && $request->get('type')==="logo"){
                                $utilisateur = $this->getDoctrine()->getRepository("BaseMainBundle:Utilisateur")->find($this->getUser()->getId());
                                $utilisateur->setAvatar($url);
                                $em->persist($utilisateur);
                                $em->flush();
                            }
                            if($request->get('table')==="societe" && $request->get('type')==="logo"){
                                $societe = $this->getDoctrine()->getRepository("BaseMainBundle:Societe")->find($request->get('table_id'));
                                $societe->setLogo($url);
                                $em->persist($societe);
                                $em->flush();
                            }
                            $reponses[]= array("id" => $fichier->getId(),"url" => "$webPath/$url","preview_url" => "$sitePath/$url", "file_name" => $fileName,"delete_url" => $this->generateUrl('fichier_delete', array('id' => $fichier->getId())));
                        }
                }else{ 
                    $erreur = $this->get('translator')->trans('extention_error');
                    
                    $response = new Response(json_encode($erreur));
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }
            }
            
            $response = new Response(json_encode($reponses));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        $form = $this->createForm('Base\MainBundle\Form\FichierType', $fichier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fichier);
            $em->flush();

            return $this->redirectToRoute('fichier_show', array('id' => $fichier->getId()));
        }

        return $this->render('fichier/new.html.twig', array(
            'fichier' => $fichier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Fichier entity.
     *
     */
    public function showAction(Fichier $fichier)
    {
        $deleteForm = $this->createDeleteForm($fichier);

        return $this->render('fichier/show.html.twig', array(
            'fichier' => $fichier,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Fichier entity.
     *
     */
    public function editAction(Request $request, Fichier $fichier)
    {
        $deleteForm = $this->createDeleteForm($fichier);
        $editForm = $this->createForm('Base\MainBundle\Form\FichierType', $fichier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fichier);
            $em->flush();

            return $this->redirectToRoute('fichier_edit', array('id' => $fichier->getId()));
        }

        return $this->render('fichier/edit.html.twig', array(
            'fichier' => $fichier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Fichier entity.
     *
     */
    public function deleteAction(Request $request, Fichier $fichier)
    {
        $form = $this->createDeleteForm($fichier);
        $form->handleRequest($request);
        
        if($request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            $url = $fichier->getUrl();
            $em->remove($fichier);
            $em->flush();
            unlink($url);
            $response = new Response(json_encode(1));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->remove($fichier);
            $em->flush();
        }
        
        return $this->redirectToRoute('fichier_index');
    }

    /**
     * Creates a form to delete a Fichier entity.
     *
     * @param Fichier $fichier The Fichier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Fichier $fichier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fichier_delete', array('id' => $fichier->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
