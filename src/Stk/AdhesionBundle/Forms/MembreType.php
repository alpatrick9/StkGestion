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
            ->add('type', ChoiceType::class, [
                'label' => 'Type du membre: ',
                'choices_as_values'=>true,
                'choices'=>['Chorale'=>'c', 'Supporteur/trice'=>'s'],
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