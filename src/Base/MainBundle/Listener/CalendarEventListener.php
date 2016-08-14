<?php

/*
 * @Zinsè ATENOUKON
 * 2016  * 
 */
namespace Base\MainBundle\Listener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
/**
 * Description of CalendarEventListener
 *
 * @author zinse
 */
class CalendarEventListener {
    
    private $entityManager;
    protected $session;
    protected $tokenStorage; //remplace tokenStorage
    protected $router;
    public function __construct(EntityManager $entityManager, SessionInterface $session,TokenStorageInterface $tokenStorage,RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->session = $session; //On définit la session courante
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        // The original request so you can get filters from the calendar
        // Use the filter in your query for example

        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');
        $base  = $this->session->get("base_url");
        $user_id = null;
        if($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser() instanceof \Base\MainBundle\Entity\Utilisateur){
            $user_id = $this->tokenStorage->getToken()->getUser()->getId();
        }
        // load events using your custom logic here,
        // for instance, retrieving events from a repository
        $companyEvents = $this->entityManager->getRepository('BaseMainBundle:Evenement')
                          ->createQueryBuilder('evenement')
                          ->where('evenement.date_debut BETWEEN :startDate and :endDate')
                          ->orWhere('evenement.date_fin is not NULL AND evenement.date_fin BETWEEN :startDate and :endDate')
                          ->andWhere('evenement.auteur = :user_id')
                          ->orWhere(':user_id MEMBER OF evenement.invite ')
                          ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                          ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                          ->setParameter('user_id', $user_id)
                          ->getQuery()->getResult();
        
       
        // $companyEvents and $companyEvent in this example
        // represent entities from your database, NOT instances of EventEntity
        // within this bundle.
        //
        // Create EventEntity instances and populate it's properties with data
        // from your own entities/database values.

        foreach($companyEvents as $companyEvent) {

            // create an event with a start/end time, or an all day event
//            if ($companyEvent->getAllDayEvent() === false) {
            if ($companyEvent->getJourneeEntiere() === false  && $companyEvent->getDateFin()!=null) {
                $eventEntity = new EventEntity($companyEvent->getTitre(), $companyEvent->getDateDebut(), $companyEvent->getDateFin());
            } else {
                $eventEntity = new EventEntity($companyEvent->getTitre(), $companyEvent->getDateDebut(), null, true);
            }
            //optional calendar event settings
            //$eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            $eventEntity->setFgColor($companyEvent->getCouleurTexte()); //set the foreground color of the event's label
            $eventEntity->setBgColor($companyEvent->getCouleurFond()); //set the background color of the event's label
            $eventEntity->addField('description', $companyEvent->getDescription());
            $eventEntity->addField('lieu', $companyEvent->getLieu());
            $eventEntity->addField('auteur', $companyEvent->getAuteur()->getUsername());
            $eventEntity->addField('annule', $companyEvent->getInactif());
            if($companyEvent->getUrl()!=null){
                //Si l'utilisateur courant est l'auteur de l'évenement, il pourra modifier
                if($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser() instanceof \Base\MainBundle\Entity\Utilisateur && $companyEvent->getAuteur() == $this->tokenStorage->getToken()->getUser()){
                    $eventEntity->setUrl($companyEvent->getUrl()); // url to send user to when event label is clicked
                    $eventEntity->setCssClass('edit-action'); // a custom class you may want to apply to event labels
                }else{
                    $eventEntity->addField('invite', 1);
                }
            }else{
                if($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser() instanceof \Base\MainBundle\Entity\Utilisateur && $companyEvent->getAuteur() == $this->tokenStorage->getToken()->getUser()){
                    $eventEntity->setUrl($this->router->generate('evenement_edit', array('id' => $companyEvent->getId())));
                    $eventEntity->setCssClass('edit-action'); // a custom class you may want to apply to event labels
                }else{
                    $eventEntity->addField('invite', 1);
                }
            }
            if($companyEvent->getInactif()==true){
                //Couleur event annulé #eee
                $eventEntity->setBgColor("#e5e5e5");
            }
            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}
