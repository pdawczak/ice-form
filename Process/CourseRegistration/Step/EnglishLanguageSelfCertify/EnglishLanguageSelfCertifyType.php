<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\EnglishLanguageSelfCertify;

use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;

class EnglishLanguageSelfCertifyType extends AbstractRegistrationStep
{

    /**
     * @var array confirmation value
     */
    protected $childFormOrder = [
        1 => 'englishFirstLanguage'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('englishFirstLanguage', 'choice', array(
                    'label' => 'Is English your first/native language?',
                    'required'=>false,
                    'expanded'=>true,
                    'multiple'=>false,
                    'choices'=>array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    )
                )
            )

        ;
        parent::buildForm($builder, $options);
    }

    public function getTitle()
    {
        return 'English language';
    }

    /**
     * @return string
     */
    public function getHtmlTemplate(){
        return 'EnglishLanguageSelfCertify.html.twig';
    }

    /**
     * @return mixed|void
     */
    public function prepare(){
        $this->setEntity(EnglishLanguageSelfCertify::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }
}
