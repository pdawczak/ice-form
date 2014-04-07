<?php
namespace Ice\FormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DisabilityType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'expanded' => false,
            'multiple' => false,
            'choices' => array(
                '00' => 'I have no disability',
                '08' => 'I have two or more impairments and/or disabling medical conditions',
                '51' => 'I have a Specific Learning Difficulty (e.g. Dyslexia/Dyspraxia/AD(H)D',
                '53' => 'I have a social/communication impairment such as Asperger’s syndrome/other autistic spectrum disorder',
                '54' => 'I have a long standing illness or health condition such as cancer, HIV, diabetes, chronic heart disease, or epilepsy',
                '55' => 'I have a mental health condition (e.g. depression/schizophrenia/anxiety disorder)',
                '56' => 'I have a physical impairment or mobility issues (e.g. difficulty using arms/using a wheelchair or crutches)',
                '57' => 'I am deaf or have a serious hearing impairment',
                '58' => 'I am blind or have a serious visual impairment uncorrected by glasses',
                '96' => 'I have a disability, impairment or medical condition not listed above',
                '97' => 'Information refused',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'disability';
    }
}