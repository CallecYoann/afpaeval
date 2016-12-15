<?php

namespace mediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use mediathequeBundle\Entity\Reservation;

class DefaultController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $ouvrages = $em->getRepository('mediathequeBundle:Ouvrage')->findAll();

        return $this->render('mediathequeBundle:Default:index.html.twig', array('Ouvrages' => $ouvrages));
    }

    public function listebdAction() {
        $em = $this->getDoctrine()->getManager();

        $repobd = $em->getRepository('mediathequeBundle:Bd');

        $qbbd = $repobd->createQueryBuilder('b')
                ->join('b.ouvrage', 'o')
                ->where('b.ouvrage = o.id');

        $ouvrages_bd = $qbbd->getQuery()->getResult();

        $repocd = $em->getRepository('mediathequeBundle:Cd');

        $qbcd = $repocd->createQueryBuilder('c')
                ->join('c.ouvrage', 'o')
                ->where('c.ouvrage = o.id');

        $ouvrages_cd = $qbcd->getQuery()->getResult();

        $repolivre = $em->getRepository('mediathequeBundle:Livre');

        $qblivre = $repolivre->createQueryBuilder('l')
                ->join('l.ouvrage', 'o')
                ->where('l.ouvrage = o.id');

        $ouvrages_livre = $qblivre->getQuery()->getResult();


        return $this->render('mediathequeBundle:Default:nouveaute.html.twig', array('ouvrages_bd' => $ouvrages_bd, 'ouvrages_cd' => $ouvrages_cd, 'ouvrages_livre' => $ouvrages_livre,
        ));
    }

    public function reservationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $actual_date = new \DateTime();
        $ouvrage_id=$request->get('id'); //on récupère l'id de l'URL et on l'affecte à une variable. | Ici le chiffre 4.
        
        $ouvrage_object = $em->getRepository('mediathequeBundle:Ouvrage')->find($ouvrage_id); //on va chercher l'id dans l'entité ouvrage et on l'affecte à une variable.
        //on récupère dans la base de donnée tout l'objet ouvrage (id, titre, annee et date) ici par ex: 4 - Les Misérables - 1862 	- 2016-12-14

        $resa = new Reservation; //On instancie un objet vide dans l'entité Reservation (une nouvelle réservation dans la base)

        $resa->setOuvrage($ouvrage_object);
        $resa->setDate($actual_date);
        $em->persist($resa);
        
        $em->flush();
        
        return $this->render('mediathequeBundle:Default:reservation.html.twig');
    }

}
