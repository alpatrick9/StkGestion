<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\ResizeFormListenerTest;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }
    
    public function homeMenuAction(Request $request) {
        return $this->render('default/home-menu.html.twig');

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("backup", name="backup")
     */
    public function backup(Request $request) {
        $host = $this->getParameter('database_host');
        $user = $this->getParameter('database_user');
        $password = $this->getParameter('database_password');
        $db_name = $this->getParameter('database_name');

        $dump = new \MySQLDump(new \mysqli($host,$user,$password,$db_name));
        $dump->save('backup/backup.sql');
        return $this->render('default/backup-done.html.twig');
    }
}
