<?php

namespace mediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use mediathequeBundle\Entity\Reservation;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use mediathequeBundle\Entity\Emprunt;

class DefaultController extends Controller {

    //Afficher tout les ouvrages.
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $ouvrages = $em->getRepository('mediathequeBundle:Ouvrage')->findAll();

        $loggedUser = $this->getUser();

        return $this->render('mediathequeBundle:Default:index.html.twig', array('Ouvrages' => $ouvrages, 'loggedUser' => $loggedUser));
    }

    //Afficher les nouveautés.
    public function listenouveauteAction() {
        $em = $this->getDoctrine()->getManager();

        $repobds = $em->getRepository('mediathequeBundle:Bd')->findAll();
        $repocds = $em->getRepository('mediathequeBundle:Cd')->findAll();
        $repolivres = $em->getRepository('mediathequeBundle:Livre')->findAll();

        $loggedUser = $this->getUser();

        return $this->render('mediathequeBundle:Default:nouveaute.html.twig', array('repobds' => $repobds, 'repocds' => $repocds, 'repolivres' => $repolivres, 'loggedUser' => $loggedUser
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

        $loggedUser = $this->getUser();

        return $this->render('mediathequeBundle:Default:listeresa.html.twig', array('mesreservations' => $mesreservations, 'loggedUser' => $loggedUser));
    }

    //Liste des réservations côté administrateur.
    public function adminlisteresaAction() {

       $em = $this->getDoctrine()->getManager();

       $loggedUser = $this->getUser();

        $adminlisteresas = $em->getRepository('mediathequeBundle:Reservation')->findAll();

        return $this->render('mediathequeBundle:Default:adminlisteresa.html.twig', array('adminlisteresas' => $adminlisteresas, 'loggedUser' => $loggedUser));
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
     public function listeempruntAction() {

       $em = $this->getDoctrine()->getManager();

       $loggedUser = $this->getUser()->getId('id');

       $mesemprunts = $em->getRepository('mediathequeBundle:Emprunt')->findByUtilisateur(array('id' => $loggedUser ));

        return $this->render('mediathequeBundle:Default:listeemprunt.html.twig', array('mesemprunts' => $mesemprunts, 'loggedUser' => $loggedUser));
    }
     
     public function evenementsAction() {
         
         return $this->render('mediathequeBundle:Default:evenements.html.twig');
     }
    
    //Afficher la liste des évènements.
     public function api_evenementsAction() {

       $em = $this->getDoctrine()->getManager();

       $loggedUser = $this->getUser()->getId('id');

       $evenements = $em->getRepository('mediathequeBundle:Evenements')->findAll();

        return $this->render('mediathequeBundle:Default:api_evenements.html.twig', array('evenements' => $evenements, 'loggedUser' => $loggedUser));
    
}

}