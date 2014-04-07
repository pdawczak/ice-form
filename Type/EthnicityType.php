<?php
namespace Ice\FormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EthnicityType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'expanded'=>false,
            'multiple'=>false,
            'choices'=>array(
                'WHITE' => 'White',
                'GYPSY' => 'Gypsy or Traveller ',
                'BCARIB' => 'Black or Black British – Caribbean',
                'BAFRICAN' => 'Black or Black British – African',
                'BOTHER' => 'Other Black background',
                'AINDIAN' => 'Asian or Asian British – Indian',
                'APAKISTN' => 'Asian or Asian British – Pakistani',
                'ABANGLAD' => 'Asian or Asian British – Bangladeshi',
                'CHINESE' => 'Chinese',
                'AOTHER' => 'Other Asian background',
                'MIXWBC' => 'Mixed-White and Black Caribbean',
                'MIXWBA' => 'Mixed-White and Black African',
                'MIXWHTAS' => 'Mixed-White and Asian',
                'ARAB' => 'Arab',
                'MIXOTHER' => 'Other Mixed background',
                'OTHER' => 'Other Ethnic background',
                'INFOREFU' => 'Information refused',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'ethnicity';
    }
}