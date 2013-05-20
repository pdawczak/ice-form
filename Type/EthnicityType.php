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
                'WHITE' => 'White (10)',
                'GYPSY' => 'Gypsy or Traveller (13) ',
                'BCARIB' => 'Black or Black British – Caribbean (21)',
                'BAFRICAN' => 'Black or Black British – African (22)',
                'BOTHER' => 'Other Black background (29)',
                'AINDIAN' => 'Asian or Asian British – Indian (31)',
                'APAKISTN' => 'Asian or Asian British – Pakistani (32)',
                'ABANGLAD' => 'Asian or Asian British – Bangladeshi (33)',
                'CHINESE' => 'Chinese (34)',
                'AOTHER' => 'Other Asian background (39)',
                'MIXWBC' => 'Mixed-White and Black Caribbean (41)',
                'MIXWBA' => 'Mixed-White and Black African (42)',
                'MIXWHTAS' => 'Mixed-White and Asian (43)',
                'ARAB' => 'Arab (50)',
                'MIXOTHER' => 'Other Mixed background (49)',
                'OTHER' => 'Other Ethnic background (80)',
                'INFOREFU' => 'Information refused (97)',
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