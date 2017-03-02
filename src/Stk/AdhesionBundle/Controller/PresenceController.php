<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 9:05 AM
 */

namespace Stk\AdhesionBundle\Controller;


use Stk\AdhesionBundle\Forms\UploadType;
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

            var_dump($excelManager->getSheet(0)->getCell('C2')->getFormattedValue());
            die();
        }

        return $this->render('StkAdhesionBundle:Presence:upload-form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}