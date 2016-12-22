<?php

namespace mediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use mediathequeBundle\Entity\Reservation;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use mediathequeBundle\Entity\Emprunt;

class DefaultController extends Controller {

    //Renvoi de la vue de l'index.
    public function indexAction() {

        return $this->render('mediathequeBundle:Default:index.html.twig');
    }

    //Afficher les nouveautés(catalogue trié)
    public function listenouveauteAction() {
        $em = $this->getDoctrine()->getManager();

        $repobds = $em->getRepository('mediathequeBundle:Bd')->findBy([], ['date' => 'DESC']);
        $repocds = $em->getRepository('mediathequeBundle:Cd')->findBy([], ['date' => 'DESC']);
        $repolivres = $em->getRepository('mediathequeBundle:Livre')->findBy([], ['date' => 'DESC']);
        
        $libres = $em->getRepository('mediathequeBundle:Reservation')->findAll();
        
        return $this->render('mediathequeBundle:Default:nouveaute.html.twig', array('repobds' => $repobds, 'repocds' => $repocds, 'repolivres' => $repolivres, 'libres' => $libres,
        ));
    }

    //Affichage du catalogue.
    public function catalogueAction() {
        $em = $this->getDoctrine()->getManager();

        $cataloguebds = $em->getRepository('mediathequeBundle:Bd')->findAll();
        $cataloguecds = $em->getRepository('mediathequeBundle:Cd')->findAll();
        $cataloguelivres = $em->getRepository('mediathequeBundle:Livre')->findAll();
        
        $disponibilites = $em->getRepository('mediathequeBundle:Reservation')->findAll();
        
        return $this->render('mediathequeBundle:Default:catalogue.html.twig', array('cataloguebds' => $cataloguebds, 'cataloguecds' => $cataloguecds, 'cataloguelivres' => $cataloguelivres, 'disponibilites' => $disponibilites,
        ));
    }
    
    //Système de réservation.
    public function reservationAction(Request $request) {

        $em = $this->getDoctrine()->getManager(); //connexion à la base de donnée
        $actual_date = new \DateTime();
        $ouvrage_id = $request->get('id'); //on récupère l'id de l'URL et on l'affecte à une variable. | Ici le chiffre 4.

        $ouvrage_object = $em->getRepository('mediathequeBundle:Ouvrage')->find($ouvrage_id); //on va chercher l'id dans l'entité ouvrage et on l'affecte à une variable.
        //on récupère dans la base de donnée tout l'objet ouvrage (id, titre, annee et date) ici par ex: 4 - Les Misérables - 1862 	- 2016-12-14

        $resa = new Reservation; //On instancie un objet vide dans l'entité Reservation (une nouvelle réservation dans la base)

        $loggedUser = $this->getUser();
        $resa->setOuvrage($ouvrage_object);
        $resa->setDate($actual_date);
        $resa->setUtilisateur($loggedUser);

        $em->persist($resa);
        $em->flush();

        return $this->render('mediathequeBundle:Default:reservation.html.twig', array('loggedUser' => $loggedUser,));
    }

    //Liste des réservations par utilisateur connecté.
    public function mesReservationsAction() {

        $em = $this->getDoctrine()->getManager();

        $mesreservations = $em->getRepository('mediathequeBundle:Reservation')->findByUtilisateur($this->getUser());

        return $this->render('mediathequeBundle:Default:resaperso.html.twig', array('mesreservations' => $mesreservations,
            ));
    }

    //Liste des réservations côté administrateur.
    public function adminlisteresaAction() {

       $em = $this->getDoctrine()->getManager();

        $adminlisteresas = $em->getRepository('mediathequeBundle:Reservation')->findAll();

        return $this->render('mediathequeBundle:Default:adminlisteresa.html.twig', array('adminlisteresas' => $adminlisteresas,
            ));
    }

    //Système de validation d'emprunt et d'écrasement de réservation.
     public function empruntAction(Request $request) {
       
         $em = $this->getDoctrine()->getManager();
         $actualdate = new \DateTime();
         
         $empruntdate = new \DateTime();
         $intervaldate = new \DateInterval('P10D');
         $retourdate = $empruntdate->add($intervaldate);
            
         $id = $request->get('id'); 
         
         $reservation_object = $em->getRepository('mediathequeBundle:Reservation')->findOneby(array('id' => $id));
            
         $emprunt = new Emprunt;
      
         $emprunt->setOuvrage($reservation_object->getOuvrage());
         $emprunt->setDateEmprunt($actualdate);
         $emprunt->setDateRetour($retourdate);
         $emprunt->setUtilisateur($reservation_object->getUtilisateur());
     
         $em->persist($emprunt);
         $em->remove($reservation_object);
         
         $em->flush();
    
         return $this->render('mediathequeBundle:Default:confirmEmprunt.html.twig'); 
     }
     
     //Afficher la liste des emprunts par utilisateur connecté.
     public function empruntPersoAction() {

       $em = $this->getDoctrine()->getManager();

       $loggedUser = $this->getUser()->getId('id');

       $mesemprunts = $em->getRepository('mediathequeBundle:Emprunt')->findByUtilisateur(array('id' => $loggedUser ));

        return $this->render('mediathequeBundle:Default:empruntperso.html.twig', array('mesemprunts' => $mesemprunts, 'loggedUser' => $loggedUser));
    }
     
     public function evenementsAction() {
         
         return $this->render('mediathequeBundle:Default:evenements.html.twig');
     }
    
    //Afficher la liste des évènements.
     public function api_evenementsAction() {

       $em = $this->getDoctrine()->getManager();
       
       $evenements = $em->getRepository('mediathequeBundle:Evenements')->findAll();

        return $this->render('mediathequeBundle:Default:api_evenements.html.twig', array('evenements' => $evenements,));
}

        public function listeempruntAction() {

       $em = $this->getDoctrine()->getManager();
     
       $listeemprunts = $em->getRepository('mediathequeBundle:Emprunt')->findAll();

        return $this->render('mediathequeBundle:Default:adminlisteemprunt.html.twig', array('listeemprunts' => $listeemprunts,
            ));

        }
}