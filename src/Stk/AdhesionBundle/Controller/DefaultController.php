<?php

namespace Stk\AdhesionBundle\Controller;

use Stk\AdhesionBundle\Entity\Membre;
use Stk\AdhesionBundle\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/*
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homeadhesion")
     */
    public function indexAction()
    {

        return $this->render('StkAdhesionBundle:Default:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/member-list/{keyword}", name="member_list")
     */
    public function listMembreAction($keyword = "") {

        /**
         * @var MembreRepository
         */
        $repository = $this->getDoctrine()->getRepository('StkAdhesionBundle:Membre');

        /**
         * @var Membre[]
         */
        $membres = [];

        if($keyword != "") {
            $membres = $repository->findByKeyword($keyword);
        } else {
            $membres = $repository->findAll();
        }

        $status = $this->getParameter('status');
        $likeAs = $this->getParameter('membre_like_as');

        return $this->render('StkAdhesionBundle:Default:member-list.html.twig', [
            'membres' => $membres,
            'status' => $status,
            'likeAs'=> $likeAs
        ]);
    }
    
    public function adhesionMenuAction() {
        return $this->render('StkAdhesionBundle:Default:adhesion-menu.html.twig');
    }
}
