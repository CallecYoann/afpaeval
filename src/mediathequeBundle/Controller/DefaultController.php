<?php

namespace mediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use mediathequeBundle\Entity\Reservation;
use Symfony\Component\HttpFoundation\Response;

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



//        $id = $em->getRepository('mediathequeBundle:Reservation')->findAll();

        return $this->render('mediathequeBundle:Default:reservation.html.twig', array('loggedUser' => $loggedUser,));
    }

    public function mesReservationsAction() {

        $em = $this->getDoctrine()->getManager();

        $mesreservations = $em->getRepository('mediathequeBundle:Reservation')->findByUtilisateur($this->getUser());

        $loggedUser = $this->getUser();

        return $this->render('mediathequeBundle:Default:listeresa.html.twig', array('mesreservations' => $mesreservations, 'loggedUser' => $loggedUser));
    }


    public function adminlisteresaAction() {

       $em = $this->getDoctrine()->getManager();

       $loggedUser = $this->getUser();

        $adminlisteresas = $em->getRepository('mediathequeBundle:Reservation')->findAll();

        return $this->render('mediathequeBundle:Default:adminlisteresa.html.twig', array('adminlisteresas' => $adminlisteresas, 'loggedUser' => $loggedUser));
    }

    // public function empruntAction(Request $request) {
    //
    //
    //
    //     $em = $this->getDoctrine()->getManager(); //connexion à la base de donnée
    //     $dateEmprunt = new \DateTime();
    //     $dateRetour = $dateEmprunt->add(new DateInterval('P10D'));
    //     $ouvrage_id = $request->get('id'); //on récupère l'id de l'URL et on l'affecte à une variable. | Ici le chiffre 4.
    //
    //     $ouvrage_object = $em->getRepository('mediathequeBundle:Ouvrage')->find($ouvrage_id); //on va chercher l'id dans l'entité ouvrage et on l'affecte à une variable.
    //     //on récupère dans la base de donnée tout l'objet ouvrage (id, titre, annee et date) ici par ex: 4 - Les Misérables - 1862 	- 2016-12-14
    //
    //     $emprunt = new Emprunt; //On instancie un objet vide dans l'entité Reservation (une nouvelle réservation dans la base)
    //
    //     $loggedUser = $this->getUser();
    //     $emprunt->setOuvrage($ouvrage_object);
    //     $emprunt->setDateEmprunt($dateEmprunt);
    //     $emprunt->setDateRetour($dateRetour);
    //     $emprunt->setUtilisateur($loggedUser);
    //
    //
    //     $em->persist($resa);
    //     $em->flush();
    //
    //     return $this->render('mediathequeBundle:Default:confirmEmprunt.html.twig', array('loggedUser' => $loggedUser,));
    //
    // }
}
