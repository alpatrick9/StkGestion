<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 9:05 AM
 */

namespace Stk\AdhesionBundle\Controller;


use Stk\AdhesionBundle\Entity\Membre;
use Stk\AdhesionBundle\Forms\UploadType;
use Stk\AdhesionBundle\Forms\YearType;
use Stk\AdhesionBundle\Models\EtatPresence;
use Stk\AdhesionBundle\Repository\PresenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PresenceController
 * @package Stk\AdhesionBundle\Controller
 * @Route("/presence")
 */
class PresenceController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/upload", name="uploadpresence")
     */
    public function uploadAction(Request $request) {
        $form = $this->createForm(new UploadType());

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            /**
             * @var $file UploadedFile
             */
            $file = $form['attachement']->getData();

            if($file->getMimeType() != $this->getParameter('upload_type') || !$file->getPathname()) {
                return $this->render('StkAdhesionBundle:Presence:upload-form.html.twig', [
                    'form' => $form->createView(),
                    'error_message'=> 'Seul un fichier excel de forma .xls peut être uploader!'
                ]);
            }
            /**
             * @var $excelManager \PHPExcel
             */
            $excelManager = $this->get('phpexcel')->createPHPExcelObject($file->getPathname());

            /**
             * à modifier par le vrai implementation
             */
            var_dump($excelManager->getSheet(0)->getCell('C2')->getFormattedValue());
            die();
        }

        return $this->render('StkAdhesionBundle:Presence:upload-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/year", name="yearpresence")
     */
    public function homePresenceAction(Request $request) {
        $form = $this->createForm(new YearType());

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            return $this->redirect($this->generateUrl('etatpresence',['year'=> $form['year']->getData()]));
        }

        return $this->render('StkAdhesionBundle:Presence:year-choice-form.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $year
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/etat/{year}", name="etatpresence")
     */
    public function etatPresenceAction(Request $request, $year) {
        
        $intervalMinuteToLate = $this->getParameter('interval_to_late');
        
        /**
         * @var $membres Membre[]
         */
        $membres = $this->getDoctrine()->getRepository("StkAdhesionBundle:Membre")->findChoralMembre();

        /**
         * @var $presenceRepository PresenceRepository
         */
        $presenceRepository = $this->getDoctrine()->getRepository("StkAdhesionBundle:Presence");

        /**
         * @var $etats EtatPresence[]
         */
        $etats = [];
        
        foreach ($membres as $membre) {
            $etat = new EtatPresence();
            $etat->setIdMembre($membre->getId());
            $etat->setFullName($membre->getLastName()." ".$membre->getFirstName());
            $etat->setNbPresence($presenceRepository->countPresenceBy($membre, $year) == null ? 0 : $presenceRepository->countPresenceBy($membre, $year)[1]);
            $etat->setNbLate($presenceRepository->countLateBy($membre, $year, $intervalMinuteToLate) == null ? 0 : $presenceRepository->countLateBy($membre, $year, $intervalMinuteToLate)[1]);

            if($etat->getNbLate() % 2 == 0) {
                $etat->setNote($etat->getNbPresence() - ($etat->getNbLate()/2));
            }
            else {
                $etat->setNote($etat->getNbPresence() - (($etat->getNbLate() - 1)/2));
            }
            array_push($etats, $etat);
        }

        usort($etats, function (EtatPresence $etat1, EtatPresence $etat2) {
            return $etat2->getNote() - $etat1->getNote();
        });
        
        return $this->render("StkAdhesionBundle:Presence:etat-presence.html.twig", [
            'year' => $year,
            'etats' => $etats
        ]);
    }
}