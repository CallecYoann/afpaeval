<?php

namespace mediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use mediathequeBundle\Entity\Reservation;

class DefaultController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $ouvrages = $em->getRepository('mediathequeBundle:Ouvrage')->findAll();
        
        $loggedUser = $this->getUser();

        return $this->render('mediathequeBundle:Default:index.html.twig', array('Ouvrages' => $ouvrages, 'loggedUser' => $loggedUser));
    }

    public function listenouveauteAction() {
        $em = $this->getDoctrine()->getManager();

        $repobds = $em->getRepository('mediathequeBundle:Bd')->findAll();

        $repocds = $em->getRepository('mediathequeBundle:Cd')->findAll();

        $repolivres = $em->getRepository('mediathequeBundle:Livre')->findAll();
        
        $loggedUser = $this->getUser();
        
        return $this->render('mediathequeBundle:Default:nouveaute.html.twig', array('repobds' => $repobds, 'repocds' => $repocds, 'repolivres' => $repolivres, 'loggedUser' => $loggedUser
        ));
    }

    public function reservationAction(Request $request) {

        $em = $this->getDoctrine()->getManager(); //connexion à la base de donnée
        $actual_date = new \DateTime();
        $ouvrage_id=$request->get('id'); //on récupère l'id de l'URL et on l'affecte à une variable. | Ici le chiffre 4.
        
        $ouvrage_object = $em->getRepository('mediathequeBundle:Ouvrage')->find($ouvrage_id); //on va chercher l'id dans l'entité ouvrage et on l'affecte à une variable.
        //on récupère dans la base de donnée tout l'objet ouvrage (id, titre, annee et date) ici par ex: 4 - Les Misérables - 1862 	- 2016-12-14

        $resa = new Reservation; //On instancie un objet vide dans l'entité Reservation (une nouvelle réservation dans la base)

        $resa->setOuvrage($ouvrage_object);
        $resa->setDate($actual_date);
        $em->persist($resa);
        
        $em->flush();
        
        
//        $id = $em->getRepository('mediathequeBundle:Reservation')->findAll();
        
        return $this->render('mediathequeBundle:Default:reservation.html.twig');
    }

    public function listeReservationAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $ids = $em->getRepository('mediathequeBundle:Reservation')->findAll();
        
        $loggedUser = $this->getUser();
        
        return $this->render('mediathequeBundle:Default:listeresa.html.twig', array('ids' => $ids, 'loggedUser' => $loggedUser));
    }
    
    public function reservationEmpruntAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $actual_date = new \DateTime();
        
    }

}
