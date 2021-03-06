<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HistoryBundle\Controller;

use HistoryBundle\Entity\histlinkSuggestion;
use HistoryBundle\Entity\linkHighlight;
use HistoryBundle\Entity\thematiqueCategory;
use HistoryBundle\Repository\eventRepository;
use HistoryBundle\Repository\linkHighlightRepository;
use HistoryBundle\Repository\linkRepository;
use HistoryBundle\Repository\personneRepository;
use HistoryBundle\Repository\thematiquePersonneRepository;
use HistoryBundle\Repository\thematiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use HistoryBundle\Entity\personne;
use HistoryBundle\Entity\place;
use HistoryBundle\Entity\event;
use HistoryBundle\Entity\admin;
use HistoryBundle\Entity\suggestion;
use HistoryBundle\Entity\link;
use HistoryBundle\Entity\thematique;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HistoryBundle\Form\Type\ContactType;

class HistoryController extends Controller{

    /**
     * @Route("/", name="history_homepage")
     */
    /*public function indexAction(Request $request){
        
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
        $success = array();
        $form = array();
        
        $repositoryS = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:suggestion");
        
        $suggestion = new suggestion();

        $form = $this->createFormBuilder($suggestion)
            ->add("person_name", TextType::class, array('label' => 'Nom'))
            ->add("place_name", TextType::class, array('label' => 'Lieu'))
            ->add("date", TextType::class, array('label' => 'Année'))
            ->add("event_name", TextType::class, array('label' => 'Événement'))
            ->add("wiki", UrlType::class, array('label' => 'Wikipédia'))
            ->add("save", SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $suggestion = $form->getData();
                
                $data = $form->getData();
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($suggestion);
                $em->flush();
                
                $success = array(true);
            }
            else{
                $errors = $this->getErrorMessages($form);
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
        
        return $this->render('HistoryBundle:History:front/index.html.twig', array("events" => $events,
                                                                            "from" => $from, 
                                                                            "to" => $to,
                                                                            "errors" => $errors,
                                                                            "success" => $success,
                                                                            "thematiques" => $thematiques,
                                                                            "eventTypes" => $eventTypesObj,
                                                                            "form" => $form->createView(),
                                                                            "data" => $data));
    }*/

    /**
     * @Route("/", name="history_homepage")
     */
    public function indexAction(Request $request){
        /* @var eventTypeRepository $repositoryET */
        $repositoryET = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("HistoryBundle:eventType");

        /* @var thematiqueRepository $repositoryT */
        $repositoryT = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("HistoryBundle:thematique");

        $from = 1000;
        $to = date("Y");

        $genders = array("Femmes" => "female", "Hommes" => "male");
        $eventTypes = $repositoryET->findBy(array(), array("nom" => "ASC"));
        $thematiques = $repositoryT->buildArrayThematiques();

        return $this->render('HistoryBundle:History:front/index.html.twig', array(
            "from" => $from,
            "to" => $to,
            "genders" => $genders,
            "eventTypes" => $eventTypes,
            "thematiques" => $thematiques
        ));
    }

    /**
     * @Route("/histlink", name="history_histlink")
     */
    public function histlinkAction(Request $request){
        /* @var linkHighlightRepository $repositoryL */
        $repositoryLH = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("HistoryBundle:linkHighlight");

        $highlights = $repositoryLH->findAll();

        return $this->render('HistoryBundle:History:front/histlink.html.twig', array(
            "highlights" => $highlights
        ));
    }

    /**
     * @Route("/histlink/{id}", name="history_histlink_personne")
     * @ParamConverter("personne", class="HistoryBundle:personne")
     */
    public function histlinkPersonneAction(personne $personne, Request $request){
        /* @var linkRepository $repositoryL */
        $repositoryL = $this->getDoctrine()
                             ->getManager()
                             ->getRepository("HistoryBundle:link");

        $personnes = $repositoryL->getPersons($personne, 3);

        $links = $repositoryL->generateLinks($personnes);

        $indexPersonnes = [];

        foreach($personnes as $key => $pers){
            $indexPersonnes[$pers->getId()] = $key + 1;
        }

        return $this->render('HistoryBundle:History:front/histlink-personne.html.twig', array(
            "links" => $links,
            "personne" => $personne,
            "personnes" => $personnes,
            "indexPersonnes" => $indexPersonnes
        ));
    }

    /**
     * @Route("/histlink/suggestion/nouvelle", name="history_histlink_suggestion")
     */
    public function histlinkSuggestionAction(Request $request){

        $suggestion = new histlinkSuggestion();

        $form = $this->createFormBuilder($suggestion)
            ->add("from_person", TextType::class, array('label' => 'Personnalité 1'))
            ->add("link", TextType::class, array('label' => 'Lien'))
            ->add("to_person", TextType::class, array('label' => 'Personnalité 2'))
            ->add("save", SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suggestion = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($suggestion);
            $em->flush();
        }

        return $this->render('HistoryBundle:History:front/histlink-suggestion.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/get-persons-search", name="history_get_persons_search", options = { "expose" = true })
     */
    public function getPersonsSearchAjaxAction(){
        /* @var personneRepository $repositoryP */
        $repositoryP = $this->getDoctrine()
            ->getManager()
            ->getRepository("HistoryBundle:personne");

        $personnes = $repositoryP->getPersons($_POST["name"], $this->container);

        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        return $response->setData(array("personnes" => $personnes));
    }

    /**
     * @Route("/get-person-route/{id}", name="history_get_persons_route", options = { "expose" = true })
     * @ParamConverter("personne", class="HistoryBundle:personne")
     */
    public function getPersonRouteAjaxAction(personne $personne, Request $request){
        /* @var eventRepository $repositoryE */
        $repositoryE = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:event");

        $events = $repositoryE->getEventsRoute($personne);

        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        return $response->setData(array("events" => $events));
    }

    /**
     * @Route("/infos-personnalite/{id}", name="history_infos_personnalite")
     * @ParamConverter("personne", class="HistoryBundle:personne")
     */
    public function infoPersonAction(personne $personne, Request $request){

        $data = array();
        $errors = array();
        $success = array();

        /* @var eventRepository $repositoryE */
        $repositoryE = $this->getDoctrine()
            ->getManager()
            ->getRepository("HistoryBundle:event");

        /* @var personneRepository $repositoryP */
        $repositoryP = $this->getDoctrine()
            ->getManager()
            ->getRepository("HistoryBundle:personne");

        /* @var thematiquePersonneRepository $repositoryTP */
        $repositoryTP = $this->getDoctrine()
            ->getManager()
            ->getRepository("HistoryBundle:thematiquePersonne");

        $events = $repositoryE->findBy(array('personne' => $personne), array('year' => 'ASC'));
        $thematiquesPersonnes = $repositoryTP->findByPersonne($personne);

        return $this->render('HistoryBundle:History:front/personne.html.twig', array(
            "personne" => $personne,
            "thematiquesPersonnes" => $thematiquesPersonnes,
            "errors" => $errors,
            "success" => $success,
            "events" => $events,
            "data" => $data));
    }

    /**
     * @Route("/suggestion-accueil", name="history_suggestions_index")
     */
    public function suggestionsIndexAction(Request $request){

        $data = array();
        $errors = array();
        $success = array();

        $suggestion = new suggestion();

        $form = $this->createFormBuilder($suggestion)
            ->add("person_name", TextType::class, array('mapped' => false, 'label' => 'Nom de la personnalité'))
            ->getForm();

        return $this->render('HistoryBundle:History:front/suggestion-index.html.twig', array(
            "errors" => $errors,
            "success" => $success,
            "form" => $form->createView(),
            "data" => $data));
    }

    /**
     * @Route("/suggestion-personnalite/{id}", name="history_suggestions_personnalite_existante")
     * @ParamConverter("personne", class="HistoryBundle:personne")
     */
    public function suggestionsExistingPersonAction(personne $personne, Request $request){

        $data = array();
        $errors = array();
        $success = array();

        /* @var eventRepository $repositoryE */
        $repositoryE = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:event");

        /* @var personneRepository $repositoryP */
        $repositoryP = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:personne");

        $form = $this->createFormBuilder(new suggestion())
            ->add("person_name", TextType::class, array('label' => 'Nom', 'attr' => array('value' => $personne->getNom(), 'readonly' => true)))
            ->add("place_name", TextType::class, array('label' => 'Lieu'))
            ->add("date", TextType::class, array('label' => 'Année'))
            ->add("event_name", TextType::class, array('label' => 'Événement'))
            ->add("wiki", UrlType::class, array('label' => 'Wikipédia', 'attr' => array('value' => $personne->getWiki(), 'readonly' => true)))
            ->add("person_id", HiddenType::class, array('mapped' => false, 'attr' => array('value' => $personne->getId())))
            ->add("save", SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /* @var suggestion $suggestion */
                $suggestion = $form->getData();

                $data = $form->getData();

                $suggestion->setPersonne($repositoryP->findOneById($form->get("person_id")->getData()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($suggestion);
                $em->flush();

                $success = array(true);
            }
            else{
                $errors = $this->getErrorMessages($form);
            }
        }

        $events = $repositoryE->findBy(array('personne' => $personne), array('year' => 'ASC'));

        return $this->render('HistoryBundle:History:front/suggestion-personne.html.twig', array(
            "personne" => $personne,
            "errors" => $errors,
            "success" => $success,
            "form" => $form->createView(),
            "events" => $events,
            "data" => $data));
    }

    /**
     * @Route("/suggestion-personnalite", name="history_suggestions")
     */
    public function suggestionsAction(Request $request){

        $data = array();
        $errors = array();
        $success = array();

        $suggestion = new suggestion();

        $form = $this->createFormBuilder($suggestion)
            ->add("person_name", TextType::class, array('label' => 'Nom'))
            ->add("place_name", TextType::class, array('label' => 'Lieu'))
            ->add("date", TextType::class, array('label' => 'Année'))
            ->add("event_name", TextType::class, array('label' => 'Événement'))
            ->add("wiki", UrlType::class, array('label' => 'Wikipédia'))
            ->add("save", SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $suggestion = $form->getData();

                $data = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($suggestion);
                $em->flush();

                $success = array(true);
            }
            else{
                $errors = $this->getErrorMessages($form);
            }
        }

        return $this->render('HistoryBundle:History:front/suggestion-form.html.twig', array(
            "errors" => $errors,
            "success" => $success,
            "form" => $form->createView(),
            "data" => $data));
    }

    /**
     * @Route("/get-events", name="history_get_events", options = { "expose" = true })
     */
    public function getEventsAjaxAction(){
        /* @var eventRepository $repositoryE */
        $repositoryE = $this->getDoctrine()
            ->getManager()
            ->getRepository("HistoryBundle:event");

        $events = $repositoryE->getEventsNew($_POST);

        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        return $response->setData(array("events" => $events));
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * @Route("/about", name="history_about")
     */
    public function aboutAction(Request $request){
        
        
        return $this->render('HistoryBundle:History:front/about-page.html.twig', array());
    }

    /**
     * @Route("/get-persons-ajax/{from}/{to}", name="history_get_persons_ajax")
     */
    public function getPersonsAjaxAction($from, $to){
        /* @var eventRepository $repositoryE */
        $repositoryE = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:event");
        
        
        $events = $repositoryE->getEvents($from, $to, $_POST);
        
        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        return $response->setData(array("events" => $events));
    }

    /**
     * @Route("/admin/index", name="history_admin_index")
     */
    public function adminindexAction(Request $request){
        $session = $request->getSession();
        
        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }
        
        return $this->render('HistoryBundle:History:admin/admin-index.html.twig', array());
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
        
        return $this->render('HistoryBundle:History:admin/admin-test.html.twig', array("thematiques" => $thematiques,
                                                                                 "personnes" => $personnes));
    }

    /**
     * @Route("/admin/new-person", name="history_new_person")
     */
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
                
                //$thematique = $repositoryTh->getThematiqueTous(1);
                //$repositoryTh->addToThematique($personne, $thematique);
                
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
                $event->setEventType($repositoryET->findOneById($data["event_type"]));
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
        
        return $this->render('HistoryBundle:History:admin/new-person.html.twig', array("data" => $data,
                                                                                 "places" => $places,
                                                                                 "eventTypes" => $eventTypes,
                                                                                 "personnes" => $personnes));
    }

    /**
     * @Route("/admin/thematiques", name="history_thematiques")
     */
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

        $repositoryTC = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:thematiqueCategory");

        $position = "";

        if($request->isMethod('POST')){
            $data = $_POST;
            
            if($data["thematique-action"] == "new-thematique"){
                $thematique = new thematique();
                
                $thematique->setNom($data["thematique-name"]);
                $thematique->setThematiqueCategory($repositoryTC->findOneById($data["thematique-category"]));

                $em = $this->getDoctrine()->getManager();
                $em->persist($thematique);
                $em->flush();
            }
            elseif($data["thematique-action"] == "add-persons"){
                $personne = $repositoryP->findOneById($data["personnes-id"]);
                
                foreach($data["thematique-id"] as $cle => $thematique_id){
                    $thematique = $repositoryT->findOneById($thematique_id);
                    $repositoryT->addToThematique($personne, $thematique);
                }

                $position = "#" . $personne->getId() . "-personne";
            }
            
        }
        
        $personnes = $repositoryP->findBy(array(), array("nom" => "ASC"));

        $thematiquesA = $repositoryT->buildArrayThematiques();

        $thematiquesCategories = $repositoryTC->findBy(array(), array("name" => "ASC"));
        
        return $this->render('HistoryBundle:History:admin/thematiques.html.twig', array("data" => $data,
                                                                                  "thematiquesA" => $thematiquesA,
                                                                                  "position" => $position,
                                                                                  "thematiquesCategories" => $thematiquesCategories,
                                                                                  "personnes" => $personnes));
    }

    /**
     * @Route("/admin/thematiques-categories", name="history_thematiques_categories")
     */
    public function thematiquesCategoriesAction(Request $request){
        $session = $request->getSession();
        $session->start();

        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }

        $data = array();

        $repositoryTC = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:thematiqueCategory");

        if($request->isMethod('POST')){
            $data = $_POST;

            if($data["thematique-category-action"] == "new-thematique-category"){
                $thematiqueCategory = new thematiqueCategory();

                $thematiqueCategory->setName($data["thematique-category-name"]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($thematiqueCategory);
                $em->flush();
            }
        }

        $thematiquesCategories = $repositoryTC->findBy(array(), array("name" => "ASC"));

        return $this->render('HistoryBundle:History:admin/thematiquesCategories.html.twig', array("data" => $data,
                                                                                  "thematiquesCategories" => $thematiquesCategories,
        ));
    }

    /**
     * @Route("/admin/links", name="history_links")
     */
    public function adminLinksAction(Request $request){
        $session = $request->getSession();
        $session->start();

        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }

        $data = array();

        $repositoryL = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:link");

        $repositoryP = $this->getDoctrine()
            ->getManager()
            ->getRepository("HistoryBundle:personne");

        if($request->isMethod('POST') && isset($_POST["link_id"])){
            $data = $_POST;

            $em = $this->getDoctrine()->getManager();
            $em->remove($repositoryL->findOneById($_POST["link_id"]));
            $em->flush();
        }

        $links = $repositoryL->findAll();
        $personnes = $repositoryP->findBy(array(), array("nom" => "asc"));

        return $this->render('HistoryBundle:History:admin/admin-links.html.twig', array("data" => $data,
                                                                                        "links" => $links,
                                                                                        "personnes" => $personnes
                                                                                        ));
    }

    /**
     * @Route("/admin/link/{id}", name="history_link")
     * @ParamConverter("personne", class="HistoryBundle:personne")
     */
    public function adminLinkAction(Personne $personne, Request $request){
        $session = $request->getSession();
        $session->start();

        if($session->get("logged") == null){
            return $this->redirect($this->generateUrl('history_login'));
        }

        $data = array();

        $repositoryL = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:link");

        $repositoryP = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:personne");

        $repositoryLT = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("HistoryBundle:linkType");

        $personnesList = $repositoryP->findBy(array(), array("nom" => "asc"));
        $linkTypes = $repositoryLT->findAll();

        if($request->isMethod('POST')){
            $data = $_POST;
            $em = $this->getDoctrine()->getManager();

            foreach($data["keys"] as $key => $cle){
                /* @var link $link */
                $link = new link();

                /* @var link $reverseLink */
                $reverseLink = new link();

                $linkType = $repositoryLT->findOneById($data["linkType"][$cle]);

                $link->setFrom($personne);
                $link->setTo($personnesList[$cle]);
                $link->setLinkType($linkType);

                $reverseLink->setFrom($personnesList[$cle]);
                $reverseLink->setTo($personne);
                $reverseLink->setLinkType($linkType->getReverseLink());

                $em->persist($link);
                $em->persist($reverseLink);
                $em->flush();
            }
        }

        return $this->render('HistoryBundle:History:admin/admin-link.html.twig', array("data" => $data,
                                                                                       "personnesList" => $personnesList,
                                                                                       "linkTypes" => $linkTypes,
                                                                                       "personne" => $personne
                                                                                      ));
    }

    /**
     * @Route("/admin/suggestions", name="history_admin_suggestions")
     */
    public function suggestionsAdminAction(Request $request){
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
                    
                    //$thematique = $repositoryTh->getThematiqueTous(1);
                    //$repositoryTh->addToThematique($personne, $thematique);

                    $em->persist($personne);

                    if($data["photo_type"] == "wiki"){
                        file_put_contents("public/personne/" . $personne->getRef() . ".jpg", file_get_contents($data["wiki_picture"]));
                    }
                    else{
                        move_uploaded_file($_FILES["person_picture"]["tmp_name"], "public/personne/" . $personne->getRef() . ".jpg");
                    }
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
                $event->setEventType($repositoryET->findOneById($data["event_type"]));
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

        $nbSuggestions = 0;

        if(!empty($suggestions)) {
            $suggestions[0]->image = $repositoryS->wikipediaImageUrls($suggestions[0]->getWiki());

            $nbSuggestions = count($suggestions);

            $suggestions = [$suggestions[0]];
        }

        return $this->render('HistoryBundle:History:admin/suggestions.html.twig', array("data" => $data,
                                                                                  "suggestions" => $suggestions,
                                                                                  "nbSuggestions" => $nbSuggestions,
                                                                                  "personnes" => $personnes,
                                                                                  "eventTypes" => $eventTypes,
                                                                                  "places" => $places));
    }

    /**
     * @Route("/admin/login", name="history_login")
     */
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
        
        return $this->render('HistoryBundle:History:admin/login.html.twig', array("errors" => $errors,
                                                                            "form" => $form->createView()));
    }

    /**
     * @Route("/admin/logout", name="history_logout")
     */
    public function logoutAction(Request $request){
        $session = $request->getSession();
        $session->start();
        $session->invalidate();
        
        return $this->redirect($this->generateUrl('history_login'));

    }

    /**
     * @Route("/contact", name="history_contact")
     */
    public function contactAction(Request $request){
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        $success = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject($form->get('subject')->getData())
                ->setFrom('histmaplb@gmail.com')
                ->setTo('berkani.lyes@gmail.com')
                ->setBody(
                    $this->renderView(
                        'HistoryBundle:History:mails/contact.html.twig',
                        array(
                            'ip' => $request->getClientIp(),
                            'name' => $form->get('name')->getData(),
                            'email' => $form->get('email')->getData(),
                            'subject' => $form->get('subject')->getData(),
                            'message' => $form->get('message')->getData()
                        )
                    )
                );

            $this->get('mailer')->send($message);

            $success = true;
        }


        return $this->render('HistoryBundle:History:front/contact.html.twig', array(
            "success" => $success,
            "form" => $form->createView())
        );
    }
}

