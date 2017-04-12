<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/1/17
 * Time: 3:19 PM
 */

namespace Stk\AdhesionBundle\Controller;


use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Stk\AdhesionBundle\Entity\Membre;
use Stk\AdhesionBundle\Forms\MembreType;
use Stk\AdhesionBundle\Forms\UploadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        $form = $this->createForm(new MembreType($this->getParameter('status_for_type'), $this->getParameter('membre_like_as_for_type')), $membre);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();

            $lastId = $em->getRepository('StkAdhesionBundle:Membre')->getMaxId();

            if(empty($lastId)) {
                $membre->setId(1);
            }
            else {
                $membre->setId($lastId + 1);
            }

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
        $form = $this->createForm(new MembreType($this->getParameter('status_for_type'), $this->getParameter('membre_like_as_for_type')), $membre);

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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PHPExcel_Exception
     * @Route("/upload", name="uploadmembre")
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
                return $this->render('StkAdhesionBundle:Adhesion:upload-form.html.twig', [
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
            //var_dump($excelManager->getSheet(0)->getCell('C2')->getFormattedValue());

            $sheet = $excelManager->getSheet(0);

            $row = 2;

            /**
             * @var $em EntityManager
             */
            $em = $this->getDoctrine()->getManager();

            while($sheet->getCellByColumnAndRow(0,$row)->getValue()) {
                $membre = new Membre();

                $membre->setId($sheet->getCellByColumnAndRow(0,$row)->getValue());
                $membre->setFirstName($sheet->getCellByColumnAndRow(1,$row)->getValue());
                $membre->setLastName($sheet->getCellByColumnAndRow(2,$row)->getValue());
                $membre->setAddress($sheet->getCellByColumnAndRow(3,$row)->getValue());
                $membre->setStatus($sheet->getCellByColumnAndRow(4,$row)->getValue());
                $membre->setLikeAs($sheet->getCellByColumnAndRow(5,$row)->getValue());

                $row++;

                $em->persist($membre);

                try{
                    $em->flush();
                } catch (ConstraintViolationException $e) {
                    if (!$em->isOpen()) {
                        $em = $em->create(
                            $em->getConnection(),
                            $em->getConfiguration()
                        );
                    }
                    continue;
                }

            }
            return $this->redirect($this->generateUrl('homeadhesion'));
        }

        return $this->render('StkAdhesionBundle:Adhesion:upload-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \PHPExcel_Exception
     * @Route("/export", name="exportmembre")
     */
    public function exportAction(Request $request) {

        /**
         * @var $phpExcelObject \PHPExcel
         */
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Stk METM Ankazomanga")
            ->setTitle("Stk METM Ankazomanga")
            ->setSubject("Liste des membres")
            ->setDescription("Liste generer à partir du logiciel de gestion!")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("List result file");

        $sheet = $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!');

        $sheet->setCellValue('A1', 'Numéro');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Prénom');
        $sheet->setCellValue('D1', 'Adresse');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'En tant que');

        /**
         * @var $membres Membre[]
         */
        $membres = $this->getDoctrine()->getRepository('StkAdhesionBundle:Membre')->findAll();

        for($i = 0; $i < sizeof($membres); $i++) {
            $row = $i + 2;
            $sheet->setCellValue('A'.$row, $membres[$i]->getId());
            $sheet->setCellValue('B'.$row, $membres[$i]->getFirstName());
            $sheet->setCellValue('C'.$row, $membres[$i]->getLastName());
            $sheet->setCellValue('D'.$row, $membres[$i]->getAddress());
            $sheet->setCellValue('E'.$row, $membres[$i]->getStatus());
            $sheet->setCellValue('F'.$row, $membres[$i]->getLikeAs());
        }

        $phpExcelObject->getActiveSheet()->setTitle('Liste des membres');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'stk membre.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}