<?php
namespace Ice\FormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeeSourceType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'expanded' => false,
            'multiple' => false,
            'choices' => array(
                '01' => 'No award or financial backing',
                '81' => 'Part or fully paid by students employer',
                '26' => 'Charitable foundation',
                '22' => 'International agency',
                '61' => 'UK industry/commerce',
                '43' => 'Overseas government',
                '45' => 'Overseas institution',
                '47' => 'Other overseas funding',
                '97' => 'Other',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'feeSource';
    }
}