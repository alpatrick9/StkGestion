<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/14/17
 * Time: 8:54 AM
 */

namespace Stk\AdhesionBundle\Forms;


use Stk\AdhesionBundle\Repository\MembreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenceType extends AbstractType
{
    private $tabHeure = [];
    private $presenceType;
    private $status;

    /**
     * PresenceType constructor.
     */
    public function __construct($presenceType, $status)
    {
        $this->tabHeure['18:00'] = new \DateTime('1970-01-01 18:00:00');
        $this->tabHeure['18:30'] = new \DateTime('1970-01-01 18:30:00');
        $this->presenceType = $presenceType;
        $this->status = $status;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('membre', EntityType::class, [
                'label'=>'Membre concerné: ',
                'class'=>'Stk\AdhesionBundle\Entity\Membre',
                'property'=>'id',
                'query_builder'=> function(MembreRepository $repository) {
                    return $repository->createQueryBuilder('membre')
                        ->where('membre.status= :status')->setParameter('status',$this->status)
                        ->orderBy('membre.lastName', 'ASC');
                },
                'choice_label'=>'lastName',
                'placeholder'=>'Choisissez'])
            ->add('isKnow', ChoiceType::class , [
                'label' => 'Presence aujourd\'hui: ',
                'choices_as_values' => true,
                'choices' => ['Oui' => true, 'Non'=> false],
                'expanded' => true,
                'multiple' => false,
                'data'=>false])
            ->add('date', DateType::class, [
                'label' => 'Date du présence: ',
                'years'=>range(date('Y'),date('Y')),
                'format'=>'dd MM yyyy',
                'placeholder'=>['year'=>'Année','day'=>'Jour','month'=>'Mois'],
                'required'=>false
            ])
            ->add('arrivedAt', TimeType::class, [
                'label' => 'Heure d\'arriver: ',
                'placeholder'=>['hour' => 'Heure', 'minute' => 'Minute'],
                'hours'=> range(16,22),
                'required'=>false
            ])
            ->add('startAt', ChoiceType::class, [
                'label' => 'Heure debut du sceance: ',
                'choices_as_values' => true,
                'choices' => $this->tabHeure,
                'expanded' => true,
                'multiple' => false
            ])
            ->add('presenceType', ChoiceType::class, [
                'label' => 'Presence de: ',
                'choices_as_values'=>true,
                'choices'=>$this->presenceType,
                'placeholder'=>'Choisissez...'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Stk\AdhesionBundle\Models\PresenceModel'
        ]);
    }

}