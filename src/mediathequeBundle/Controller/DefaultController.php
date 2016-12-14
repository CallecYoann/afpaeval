<?php

namespace mediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        

        return $this->render('mediathequeBundle:Default:index.html.twig', array('ouvrages_bd' => $ouvrages_bd, 'ouvrages_cd' => $ouvrages_cd, 'ouvrages_livre' => $ouvrages_livre,
        ));
    }

//    public function listecdAction() {
//        $em = $this->getDoctrine()->getManager();
//
//        $repo = $em->getRepository('mediathequeBundle:Cd');
//
//        $qb = $repo->createQueryBuilder('c')
//                ->join('c.ouvrage', 'o')
//                ->where('c.ouvrage = o.id');
//
//        $ouvrages_cd = $qb->getQuery()->getResult();
//
//
//        return $this->render('mediathequeBundle:Default:index.html.twig', array('ouvrages_cd' => $ouvrages_cd,
//        ));
//    }
    
}
