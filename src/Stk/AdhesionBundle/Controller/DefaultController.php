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
        /**
         * @var MembreRepository
         */
        $repository = $this->getDoctrine()->getRepository('StkAdhesionBundle:Membre');

        /**
         * @var Membre[]
         */
        $membres = $repository->findAll();

        $type = $this->getParameter('type');

        return $this->render('StkAdhesionBundle:Default:index.html.twig', [
            'membres' => $membres,
            'type' => $type
        ]);
    }
    
    public function adhesionMenuAction() {
        return $this->render('StkAdhesionBundle:Default:adhesion-menu.html.twig');
    }
}
