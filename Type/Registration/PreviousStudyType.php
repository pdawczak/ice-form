<?php

namespace Ice\FormBundle\Type\Registration;

use Symfony\Component\Form\FormBuilderInterface;

abstract class PreviousStudyType extends AbstractRegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', 'integer')
            ->add('type')
            ->add('dueDate', 'date', array(
                'input'=>'datetime',
                'widget'=>'single_text',
                'format'=>'yyyy-MM-dd')
        )
        ;
    }
}