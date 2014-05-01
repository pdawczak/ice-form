<?php

namespace Ice\FormBundle\Process\CourseApplication\Step\Account\V1;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExistingAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['step']);
        $resolver->setDefaults(['data_class'=>'Ice\FormBundle\Process\CourseApplication\Step\Account\V1\AccountData']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'child';
    }
}
