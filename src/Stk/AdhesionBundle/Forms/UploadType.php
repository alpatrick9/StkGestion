<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 9:01 AM
 */

namespace Stk\AdhesionBundle\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attachement', FileType::class, [
                'label' => 'Fichier excel:'
            ]);
    }

}