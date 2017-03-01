<?php

namespace Stk\FinanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package Stk\FinanceBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homefinance")
     */
    public function indexAction()
    {
        return $this->render('StkFinanceBundle:Default:index.html.twig');
    }

    public function financeMenuAction() {
        return $this->render('StkFinanceBundle:Default:finance-menu.html.twig');
    }
}
