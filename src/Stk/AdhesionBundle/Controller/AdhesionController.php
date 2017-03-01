<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/1/17
 * Time: 3:19 PM
 */

namespace Stk\AdhesionBundle\Controller;


use Stk\AdhesionBundle\Entity\Membre;
use Stk\AdhesionBundle\Forms\MembreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdhesionController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/add", name="newadhesion")
     */
    public function newAdhesionAction(Request $request) {
        $membre = new Membre();
        $form = $this->createForm(new MembreType(), $membre);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();
            return $this->redirect($this->generateUrl('homeadhesion'));
        }
        return $this->render('StkAdhesionBundle:Adhesion:adhesion-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Membre $membre
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/edit/{id}", name="editmembre")
     */
    public function updateAction(Request $request, Membre $membre) {
        $form = $this->createForm(new MembreType(), $membre);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('homeadhesion'));
        }
        return $this->render('StkAdhesionBundle:Adhesion:adhesion-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Membre $membre
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("delete/{id}", name="deletemembre")
     */
    public function deleteAction(Request $request, Membre $membre) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($membre);
        $em->flush();
        return $this->redirect($this->generateUrl('homeadhesion'));
    }
}