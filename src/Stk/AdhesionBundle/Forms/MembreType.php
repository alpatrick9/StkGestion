<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/1/17
 * Time: 3:10 PM
 */

namespace Stk\AdhesionBundle\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreType extends AbstractType
{
    private $status;
    private $likeAs;

    /**
     * MembreType constructor.
     * @param $status
     * @param $likeAs
     */
    public function __construct($status, $likeAs)
    {
        $this->status = $status;
        $this->likeAs = $likeAs;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nom: '
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prénom: ',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse: ',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status: ',
                'choices_as_values'=>true,
                'choices'=>$this->status,
                'placeholder'=>'Choisissez ...'
            ])
            ->add('likeAs', ChoiceType::class, [
                'label' => 'Membre en tant que: ',
                'choices_as_values'=>true,
                'choices'=>$this->likeAs,
                'placeholder'=>'Choisissez...'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Stk\AdhesionBundle\Entity\Membre'
        ]);
    }

}