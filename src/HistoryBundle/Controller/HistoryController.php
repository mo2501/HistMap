<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HistoryBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HistoryBundle\Entity\personne;
use HistoryBundle\Entity\place;
use HistoryBundle\Entity\event;
use HistoryBundle\Entity\admin;
use HistoryBundle\Entity\suggestion;
use HistoryBundle\Entity\thematique;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class HistoryController extends Controller{
    
    public function indexAction(Request $request){
        
        $repositoryE = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:event");
        
        $repositoryET = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:eventType");
        
        $repositoryTh = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:thematique");
        
        $data = array();
        $errors = array();
        
        if($request->isMethod('POST')){
            $data = $_POST;

            if(@$data["form_action"] == "add_suggestion"){
                
                if($data["event"] == "" || !preg_match('/^[a-zA-Z0-9 -ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/', $data["event"])){
                    $errors[] = "event";
                }
                
                if($data["date"] == "" || !is_numeric($data["date"])){
                    $errors[] = "date";
                }
                
                if($data["person_name"] == "" || !preg_match('/^[a-zA-Z0-9 -ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/', $data["person_name"])){
                    $errors[] = "person-name";
                }
                
                if($data["place_name"] == "" || !preg_match('/^[a-zA-Z0-9 -ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/', $data["place_name"])){
                    $errors[] = "place-name";
                }
                
                if($data["wiki"] == ""){
                    $errors[] = "wiki";
                }
                else{
                    $pieces = parse_url($data["wiki"]);
                    $domain = isset($pieces['host']) ? $pieces['host'] : '';
                    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
                        $url = $regs['domain'];
                        if($url != "wikipedia.org"){
                            $errors[] = "wiki";
                        }
                    }
                    else{
                        $errors[] = "wiki";
                    }
                }
                
                if(empty($errors)){
                    $suggestion = new suggestion();
                    
                    $suggestion->setPersonName($data["person_name"]);
                    $suggestion->setPlaceName($data["place_name"]);
                    $suggestion->setDate($data["date"]);
                    $suggestion->setWiki($data["wiki"]);
                    $suggestion->setEventName($data["event"]);
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($suggestion);
                    $em->flush();
                }
            }
        }
        
        $from = 1000;
        $to = date("Y");
        $data_filter = array();
        
        $data_filter["gender"] = array("male" => "true", "female" => "true");
        
        $eventTypesObj = $repositoryET->findBy(array(), array("nom" => "ASC"));
        
        $eventTypes = array();
        
        foreach($eventTypesObj as $key => $type){
            $eventTypes[] = $type->getId();
        }
        
        $data_filter["event"] = $eventTypes;
        
        $events = $repositoryE->getEvents($from, $to, $data_filter);
        
        $thematiques = $repositoryTh->findAllCustom();
        
        return $this->render('HistoryBundle:History:index.html.twig', array("events" => $events, 
                                                                            "from" => $from, 
                                                                            "to" => $to,
                                                                            "errors" => $errors,
                                                                            "thematiques" => $thematiques,
                                                                            "eventTypes" => $eventTypesObj,
                                                                            "data" => $data));
    }
    
    public function aboutAction(Request $request){
        
        
        return $this->render('HistoryBundle:History:about-page.html.twig', array());
    }
    
    public function getPersonsAjaxAction($from, $to){
        $repositoryE = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:event");
        
        $repositoryPe = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:personne");
        
        $repositoryPl = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:place");
        
        
        $events = $repositoryE->getEvents($from, $to, $_POST);
        
        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        return $response->setData(array("events" => $events));
    }
    
    public function adminindexAction(Request $request){
        $session = $request->getSession();
        
        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }
        
        return $this->render('HistoryBundle:History:admin-index.html.twig', array());
    }
    
    public function admintestAction(Request $request){
        $session = $request->getSession();
        
        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }
        
        $repositoryTh = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:thematique");
        
        $repositorP = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:personne");
        
        $thematiques = $repositoryTh->findAllCustom();
        
        $personnes = $repositoryTh->getIdPersonnesByThematique(10);
        
        return $this->render('HistoryBundle:History:admin-test.html.twig', array("thematiques" => $thematiques,
                                                                                 "personnes" => $personnes));
    }
    
    public function newPersonAction(Request $request){
        $session = $request->getSession();
        $session->start();
        
        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }
        
        $data = array();
            
        $repositoryE = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:event");
        
        $repositoryET = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:eventType");

        $repositoryPe = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:personne");

        $repositoryPl = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:place");

        $repositoryTh = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:thematique");
        
        $personnes = $repositoryPe->findAll();
        $places = $repositoryPl->findAll();
        
        if($request->isMethod('POST')){
            $data = $_POST;
                
            $em = $this->getDoctrine()->getManager();
            
            if($data["new_person_action"] == "create_person"){
                $personne = new personne();
                
                $personne->setNom($data["person_name"]);
                
                $generator = $this->container->get('history.generator');
                $ref = $generator->generateRef();
                
                $personne->setRef($ref);
                $personne->setWiki($data["person_wiki"]);
                $personne->setGender($data["gender"]);
                
                $thematique = $repositoryTh->getThematiqueTous(1);
                $repositoryTh->addToThematique($personne, $thematique);
                
                $em->persist($personne);
                
                move_uploaded_file($_FILES["person_picture"]["tmp_name"], "public/personne/" . $personne->getRef() . ".jpg");
            }
            elseif($data["new_person_action"] == "create_place"){
                $place = new place();
                
                $place->setNom($data["place_name"]);
                $place->setLat($data["place_lat"]);
                $place->setLng($data["place_lng"]);
                
                $em->persist($place);
            }
            elseif($data["new_person_action"] == "create_event"){
                $event = new event();
                
                $event->setIntitule($data["event_name"]);
                $event->setEventType($data["event_type"]);
                $event->setYear($data["event_year"]);
                $event->setPersonne($repositoryPe->findOneById($data["personne_id"]));
                $event->setPlace($repositoryPl->findOneById($data["place_id"]));
                
                $em->persist($event);
            }
            
            $em->flush();
        }
        
        $personnes = $repositoryPe->findBy(array(), array("nom" => "ASC"));
        $places = $repositoryPl->findBy(array(), array("nom" => "ASC"));
        $eventTypes = $repositoryET->findAll();
        
        return $this->render('HistoryBundle:History:new-person.html.twig', array("data" => $data,
                                                                                 "places" => $places,
                                                                                 "eventTypes" => $eventTypes,
                                                                                 "personnes" => $personnes));
    }
    
    public function thematiquesAction(Request $request){
        $session = $request->getSession();
        $session->start();
        
        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }
        
        $data = array();
            
        $repositoryT = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:thematique");
            
        $repositoryP = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:personne");
        
        if($request->isMethod('POST')){
            $data = $_POST;
            
            if($data["thematique-action"] == "new-thematique"){
                $thematique = new thematique();
                
                $thematique->setNom($data["thematique-name"]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($thematique);
                $em->flush();
            }
            elseif($data["thematique-action"] == "add-persons"){
                $thematique = $repositoryT->findOneById($data["thematique-id"]);
                
                foreach($data["personnes-id"] as $cle => $personne_id){
                    $personne = $repositoryP->findOneById($personne_id);
                    $repositoryT->addToThematique($personne, $thematique);
                }
            }
            
        }
        
        $personnes = $repositoryP->findBy(array(), array("nom" => "ASC"));
        
        $thematiques = $repositoryT->findBy(array(), array("nom" => "ASC"));
        
        return $this->render('HistoryBundle:History:thematiques.html.twig', array("data" => $data,
                                                                                  "thematiques" => $thematiques,
                                                                                  "personnes" => $personnes));
    }
    
    public function suggestionsAction(Request $request){
        $session = $request->getSession();
        $session->start();
        
        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }
        
        $data = array();
            
        $repositoryE = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:event");
        
        $repositoryET = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:eventType");

        $repositoryPe = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:personne");

        $repositoryPl = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:place");
            
        $repositoryS = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:suggestion");
            
        $repositoryTh = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:thematique");
        
        if($request->isMethod('POST')){
            $data = $_POST;
            $em = $this->getDoctrine()->getManager();
            
            if($data["suggestion_action"] == "validate"){
                if($data["new_person"] == "true"){
                    $personne = new personne();
                
                    $personne->setNom($data["person_name"]);
                    
                    $generator = $this->container->get('history.generator');
                    $ref = $generator->generateRef();
                    
                    $personne->setRef($ref);
                    $personne->setWiki($data["wiki"]);
                    $personne->setGender($data["gender"]);
                    
                    $thematique = $repositoryTh->getThematiqueTous(1);
                    $repositoryTh->addToThematique($personne, $thematique);

                    $em->persist($personne);

                    move_uploaded_file($_FILES["person_picture"]["tmp_name"], "public/personne/" . $personne->getRef() . ".jpg");
                }
                else{
                    $personne = $repositoryPe->findOneById($data["person_id"]);
                }
                
                if($data["new_place"] == "true"){
                    $place = new place();
                
                    $place->setNom($data["place_name"]);
                    $place->setLat($data["place_lat"]);
                    $place->setLng($data["place_lng"]);

                    $em->persist($place);
                }
                else{
                    $place = $repositoryPl->findOneById($data["place_id"]);
                }
                
                $event = new event();
                
                $event->setIntitule($data["event_name"]);
                $event->setEventType($data["event_type"]);
                $event->setYear($data["date"]);
                $event->setPersonne($personne);
                $event->setPlace($place);
                
                $em->persist($event);
            }
            
            $suggestion = $repositoryS->findOneById($data["suggestion_id"]);
            
            $em->remove($suggestion);
            $em->flush();
        }
        
        $personnes = $repositoryPe->findBy(array(), array("nom" => "ASC"));
        $places = $repositoryPl->findBy(array(), array("nom" => "ASC"));
        $suggestions = $repositoryS->findAll();
        $eventTypes = $repositoryET->findAll();
        
        return $this->render('HistoryBundle:History:suggestions.html.twig', array("data" => $data,
                                                                                  "suggestions" => $suggestions,
                                                                                  "personnes" => $personnes,
                                                                                  "eventTypes" => $eventTypes,
                                                                                  "places" => $places));
    }
    
    public function loginAction(Request $request){
        $session = $request->getSession();
        $session->start();
        
        if($session->get("userid") != null){
            return $this->redirect($this->generateUrl('history_admin_index'));
        }
            
        $repositoryA = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:admin");
        
        $admin = new admin();

        $form = $this->createFormBuilder($admin)
            ->add("username", TextType::class)
            ->add("password", PasswordType::class)
            ->add("save", SubmitType::class, array('label' => 'Valider'))
            ->getForm();

        $form->handleRequest($request);
        
        $errors = "";

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $result = $repositoryA->compareLogin($data);
            
            if($result){
                $session->set("logged", "true");
                return $this->redirect($this->generateUrl('history_admin_index'));
            }
            else{
                $errors = "Identifiants incorrects";
            }
        }
        
        return $this->render('HistoryBundle:History:login.html.twig', array("errors" => $errors,
                                                                            "form" => $form->createView()));
    }
    
    public function logoutAction(Request $request){
        $session = $request->getSession();
        $session->start();
        $session->invalidate();
        
        return $this->redirect($this->generateUrl('history_login'));

    }
}

