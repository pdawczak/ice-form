<?php
namespace Ice\FormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QualificationLevelType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'expanded' => false,
            'multiple' => false,
            'choices' => array(
                'X05' => 'No formal qualification',
                'P50' => 'A or AS level',
                'C20' => 'Certificate of Higher Education - CertHE',
                'J20' => 'Diploma of Higher Education - DipHE',
                'J10' => 'Foundation degree',
                'JUK' => 'UK first degree',
                'HUK' => 'UK first degree with honours',
                'MUK' => 'UK masters degree',
                'DUK' => 'UK doctorate degree',
                'HZZ' => 'Non-UK first degree',
                'MZZ' => 'Non-UK masters degree',
                'DZZ' => 'Non-UK doctorate degree',
                'M2X' => 'Integrated UG or PG masters degree on the enhanced extended pattern',
                'M41' => 'Diploma at level M',
                'M44' => 'Certificate at level M',
                'M80' => 'Other taught qual at level M',
                'M90' => 'Taught work at level M for institutional credit',
                'H71' => 'Professional Grad Cert in Education',
                'M71' => 'Postgrad Cert or Prof Grad Dip in Education',
                'H11' => '1st degree with hon leading to QTS reg with a GTC',
                'J48' => 'CertEd or DipEd i.e. non-grad initial teacher training qualification',
                'J30' => 'Higher National Diploma - HND',
                'C30' => 'Higher National Certificate - HNC',
                'C80' => 'Other qualification at level C',
                'D80' => 'Other qualification at level D',
                'H80' => 'Other qualification at level H',
                'J80' => 'Other qualification at level J',
                'J49' => 'Foundation course at level J',
                'C44' => 'Higher Apprenticeship - level 4',
                'C90' => 'Undergraduate credits',
                'P41' => 'Diploma at level 3',
                'P42' => 'Certificate at level 3',
                'P46' => 'Award at level 3',
                'P51' => '14-19 Advanced Diploma - level 3',
                'Q51' => '14-19 Higher Diploma - level 2',
                'R51' => '14-19 Foundation Diploma - level 1',
                'P47' => 'AQA Baccalaureate - Bacc',
                'P62' => 'International Baccalaureate Diploma',
                'P63' => 'International Baccalaureate Certificate',
                'P53' => 'Scottish Baccalaureate',
                'P68' => 'Welsh Baccalaureate Advanced Diploma - level 3',
                'Q52' => 'Welsh Baccalaureate Intermediate Diploma - level 2',
                'R52' => 'Welsh Baccalaureate Foundation Diploma - level 1',
                'P64' => 'Cambridge Pre-U Diploma',
                'P65' => 'Cambridge Pre-U Certificate',
                'P91' => 'Level 3 quals subject to UCAS tariff',
                'P92' => 'Level 3 quals not subject to UCAS tariff',
                'P80' => 'Other qualification at level 3',
                'Q80' => 'Other qualification at level 2',
                'R80' => 'Other qualification at level 1',
                'X00' => 'HE access course QAA recognised',
                'X01' => 'HE access course not QAA recognised',
                'X04' => 'Other qualification level not known',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'qualificationLevel';
    }
}