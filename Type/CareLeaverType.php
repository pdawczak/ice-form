<?php
namespace Ice\FormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CareLeaverType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'expanded' => false,
            'multiple' => false,
            'choices' => array(
                'I am a Care Leaver' => 'I am a Care Leaver',
                'I am not a Care Leaver' => 'I am not a Care Leaver',
                'I do not wish to declare' => 'I do not wish to declare',
                'I do not know' => 'I do not know',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'careLeaverType';
    }
}
