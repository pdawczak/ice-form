<?php
namespace Ice\FormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DisplayType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'disabled'=>true
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'display';
    }
}