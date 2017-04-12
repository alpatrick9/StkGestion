<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 9:05 AM
 */

namespace Stk\AdhesionBundle\Controller;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Stk\AdhesionBundle\Entity\Membre;
use Stk\AdhesionBundle\Entity\Presence;
use Stk\AdhesionBundle\Entity\PresenceBC;
use Stk\AdhesionBundle\Forms\PresenceType;
use Stk\AdhesionBundle\Forms\UploadType;
use Stk\AdhesionBundle\Forms\YearType;
use Stk\AdhesionBundle\Models\EtatPresence;
use Stk\AdhesionBundle\Models\EtatPresenceBC;
use Stk\AdhesionBundle\Models\PresenceModel;
use Stk\AdhesionBundle\Repository\PresenceBCRepository;
use Stk\AdhesionBundle\Repository\PresenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

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
     * titre de memoire mais n'est pas utiliser dans le projet
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(new UploadType());

        $typePresenceFormBuilder = $this->createFormBuilder();

        $typePresenceFormBuilder->add('type', ChoiceType::class, [
            'label' => 'Type de presence:',
            'choices_as_values' => true,
            'choices' => ['Tous les membres' => 'tm', 'Bureau/Commite' => 'bc'],
            'placeholder' => 'Choisissez...'
        ]);

        $typePresenceForm = $typePresenceFormBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $typePresenceForm->handleRequest($request);

            /**
             * @var $file UploadedFile
             */
            $file = $form['attachement']->getData();

            if ($file->getMimeType() != $this->getParameter('upload_type') || !$file->getPathname()) {
                return $this->render('StkAdhesionBundle:Presence:upload-form.html.twig', [
                    'form' => $form->createView(),
                    'typeForm' => $typePresenceForm->createView(),
                    'error_message' => 'Seul un fichier excel de forma .xls peut être uploader!'
                ]);
            }
            /**
             * @var $excelManager \PHPExcel
             */
            $excelManager = $this->get('phpexcel')->createPHPExcelObject($file->getPathname());

            $sheet = $excelManager->getSheet(0);

            $type = $typePresenceForm['type']->getData();

            /**
             * @var $em EntityManager
             */
            $em = $this->getDoctrine()->getManager();

            switch ($type) {
                case 'bc';
                    if ($sheet->getCellByColumnAndRow(0, 1)->getValue() != 'Date' || $sheet->getCellByColumnAndRow(1, 1)->getValue() != 'Membre' || $sheet->getCellByColumnAndRow(2, 1)->getValue() != null) {
                        return $this->render('StkAdhesionBundle:Presence:upload-form.html.twig', [
                            'form' => $form->createView(),
                            'error_message' => 'Erreur de fichier excel, strucure non comform aux données du presence de bureau/commit!',
                            'typeForm' => $typePresenceForm->createView()
                        ]);
                    }
                    $row = 2;
                    while ($sheet->getCellByColumnAndRow(0, $row)->getValue()) {
                        $presenceBc = new PresenceBC();

                        $excelDate = $sheet->getCellByColumnAndRow(0, $row)->getValue();

                        $date = \DateTime::createFromFormat("d-m-Y", (date("d-m-Y", \PHPExcel_Shared_Date::ExcelToPHP($excelDate))));

                        $presenceBc->setDate($date);

                        /**
                         * @var $membre Membre
                         */
                        $membre = $this->getDoctrine()->getRepository('StkAdhesionBundle:Membre')->find($sheet->getCellByColumnAndRow(1, $row)->getValue());

                        $presenceBc->setMembre($membre);

                        $row++;

                        /**
                         * @var $repository PresenceBCRepository
                         */
                        $repository = $this->getDoctrine()->getRepository('StkAdhesionBundle:PresenceBC');
                        if ($repository->isExist($presenceBc->getMembre(), $presenceBc->getDate())) {
                            continue;
                        }

                        $em->persist($presenceBc);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('yearpresencebc'));
                default:
                    break;
            }
        }

        return $this->render('StkAdhesionBundle:Presence:upload-form.html.twig', [
            'form' => $form->createView(),
            'typeForm' => $typePresenceForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/year", name="yearpresence")
     */
    public function homePresenceAction(Request $request)
    {
        $form = $this->createForm(new YearType());

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            return $this->redirect($this->generateUrl('etatpresence', ['year' => $form['year']->getData()]));
        }

        return $this->render('StkAdhesionBundle:Presence:year-choice-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/yearbc", name="yearpresencebc")
     */
    public function homePresenceBcAction(Request $request)
    {
        $form = $this->createForm(new YearType());

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            return $this->redirect($this->generateUrl('etatpresencebc', ['year' => $form['year']->getData()]));
        }

        return $this->render('StkAdhesionBundle:Presence:year-choice-form-bc.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $year
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/etat/{year}", name="etatpresence")
     */
    public function etatPresenceAction(Request $request, $year)
    {

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
            $etat->setFullName($membre->getLastName() . " " . $membre->getFirstName());
            $etat->setNbPresence($presenceRepository->countPresenceBy($membre, $year) == null ? 0 : $presenceRepository->countPresenceBy($membre, $year)[1]);
            $etat->setNbLate($presenceRepository->countLateBy($membre, $year, $intervalMinuteToLate) == null ? 0 : $presenceRepository->countLateBy($membre, $year, $intervalMinuteToLate)[1]);

            if ($etat->getNbLate() % 2 == 0) {
                $etat->setNote($etat->getNbPresence() - ($etat->getNbLate() / 2));
            } else {
                $etat->setNote($etat->getNbPresence() - (($etat->getNbLate() - 1) / 2));
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

    /**
     * @param Request $request
     * @param $year
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/etat-bc/{year}", name="etatpresencebc")
     */
    public function etatPresenceBureauAction(Request $request, $year)
    {

        $intervalMinuteToLate = $this->getParameter('interval_to_late');

        /**
         * @var $membres Membre[]
         */
        $membres = $this->getDoctrine()->getRepository("StkAdhesionBundle:Membre")->findChoralBC();

        /**
         * @var $presenceRepository PresenceBCRepository
         */
        $presenceRepository = $this->getDoctrine()->getRepository("StkAdhesionBundle:PresenceBC");

        /**
         * @var $etats EtatPresenceBC[]
         */
        $etats = [];

        foreach ($membres as $membre) {
            $etat = new EtatPresence();
            $etat->setIdMembre($membre->getId());
            $etat->setFullName($membre->getLastName() . " " . $membre->getFirstName());
            $etat->setNbPresence($presenceRepository->countPresenceBy($membre, $year) == null ? 0 : $presenceRepository->countPresenceBy($membre, $year)[1]);

            array_push($etats, $etat);
        }

        usort($etats, function (EtatPresence $etat1, EtatPresence $etat2) {
            return $etat2->getNote() - $etat1->getNote();
        });

        return $this->render("StkAdhesionBundle:Presence:etat-presence-bc.html.twig", [
            'year' => $year,
            'etats' => $etats
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("delete", name="deletepresence")
     */
    public function deleteAction(Request $request)
    {
        /**
         * @var $presenceRepository PresenceRepository
         */
        $presenceRepository = $this->getDoctrine()->getRepository("StkAdhesionBundle:Presence");

        /**
         * @var $presenceBcRepository PresenceBCRepository
         */
        $presenceBcRepository = $this->getDoctrine()->getRepository("StkAdhesionBundle:PresenceBC");

        /**
         * @var string[]
         */
        $presenceYears = $presenceRepository->yearsDistinct();

        /**
         * @var string[]
         */
        $presenceBcYear = $presenceBcRepository->yearsDistinct();

        /**
         * @var integer[]
         */
        $year = [];

        foreach ($presenceYears as $value) {
            $year[$value[1]] = intval($value[1]);
        }

        foreach ($presenceBcYear as $value) {
            if (in_array($value[1], $year)) {
                continue;
            }
            $year[$value[1]] = intval($value[1]);
        }

        if (empty($year)) {
            $year = range(date('Y'), date('Y'));
            $year[$year[0]] = $year[0];
            unset($year[0]);
        }

        $form = $this->createForm(new YearType($year));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $currentYear = $form['year']->getData();
            $presenceBcRepository->deleteByYear($currentYear);
            $presenceRepository->deleteByYear($currentYear);
            return $this->redirect($this->generateUrl('homeadhesion'));
        }

        return $this->render('StkAdhesionBundle:Presence:delete-form.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add", name="addpresence")
     */
    public function addAction(Request $request)
    {
        $presenceModel = new PresenceModel();
        $form = $this->createForm(new PresenceType(), $presenceModel);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            /**
             * @var $em EntityManager
             */
            $em = $this->getDoctrine()->getManager();

            switch ($presenceModel->getPresenceType()) {
                case 'm':
                    $presenceMembre = new Presence();
                    $presenceMembre->setDate($presenceModel->isIsKnow() ? new \DateTime() : $presenceModel->getDate());

                    if ($presenceModel->getArrivedAt() == null) {
                        return $this->render('StkAdhesionBundle:Presence:add-presence-form.html.twig', [
                            'form' => $form->createView(),
                            'error_msg' => 'Le champ heure d\'arriver est obligatoire!'
                        ]);
                    }

                    $time_start_minute = intval(date('H', $presenceModel->getStartAt()->getTimestamp())) * 60 + intval(date('i', $presenceModel->getStartAt()->getTimestamp()));
                    $time_arrived_minute = intval(date('H', $presenceModel->getArrivedAt()->getTimestamp())) * 60 + intval(date('i', $presenceModel->getArrivedAt()->getTimestamp()));
                    $late_time = $time_arrived_minute - $time_start_minute;

                    $presenceMembre->setLateTime($late_time);
                    $presenceMembre->setMembre($presenceModel->getMembre());

                    /**
                     * @var $repository PresenceRepository
                     */
                    $repository = $this->getDoctrine()->getRepository('StkAdhesionBundle:Presence');

                    if ($repository->isExist($presenceMembre->getMembre(), $presenceMembre->getDate())) {
                        return $this->render('StkAdhesionBundle:Presence:add-presence-form.html.twig', [
                            'form' => $form->createView(),
                            'error_msg' => 'Ces informations sont déjà enregistrées!'
                        ]);
                    }

                    $em->persist($presenceMembre);
                    $em->flush();
                    break;
                case 'bc':

                    $presenceBc = new PresenceBC();

                    $presenceBc->setDate($presenceModel->isIsKnow() ? new \DateTime() : $presenceModel->getDate());
                    $presenceBc->setMembre($presenceModel->getMembre());

                    $likeAs = $presenceBc->getMembre()->getLikeAs();

                    if($likeAs != 'b' && $likeAs != 'c') {
                        return $this->render('StkAdhesionBundle:Presence:add-presence-form.html.twig', [
                            'form' => $form->createView(),
                            'error_msg' => 'Le membre séléctionné n\'est pas membre du bureau ou ni du commité'
                        ]);
                    }
                    /**
                     * @var $repository PresenceBCRepository
                     */
                    $repository = $this->getDoctrine()->getRepository('StkAdhesionBundle:PresenceBC');
                    if ($repository->isExist($presenceBc->getMembre(), $presenceBc->getDate())) {
                        return $this->render('StkAdhesionBundle:Presence:add-presence-form.html.twig', [
                            'form' => $form->createView(),
                            'error_msg' => 'Ces informations sont déjà enregistrées!'
                        ]);
                    }

                    $em->persist($presenceBc);
                    $em->flush();
                    break;
                default:
                    break;
            }
            $presenceModel = new PresenceModel();
            $form = $this->createForm(new PresenceType(), $presenceModel);
            return $this->render('StkAdhesionBundle:Presence:add-presence-form.html.twig', [
                'form' => $form->createView(),
                'info_msg' => 'Presence bien enregistrér!'
            ]);
        }
        return $this->render('StkAdhesionBundle:Presence:add-presence-form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}