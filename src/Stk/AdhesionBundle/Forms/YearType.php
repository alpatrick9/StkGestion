<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 12:43 PM
 */

namespace Stk\AdhesionBundle\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class YearType extends AbstractType
{
    /**
     * @var $years string[]
     */
    private $years;

    /**
     * @var $choices string[]
     */
    private $choices;

    /**
     * YearType constructor.
     */
    public function __construct()
    {
        $this->years = range(date('Y')-5, date('Y'));
        foreach ($this->years as $year) {
            $this->choices[$year] = $year;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', ChoiceType::class, [
                'label' => 'Année: ',
                'choices_as_values'=>true,
                'choices'=> $this->choices,
                'placeholder'=>'Choisissez...'
            ]);
    }

}