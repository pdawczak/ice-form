<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\TermsAndConditions;

use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Tests\Fixtures\Person;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ice\JanusClientBundle\Entity\User;

class TermsAndConditionsType extends AbstractRegistrationStep
{

    /**
     * @var array confirmation value
     */
    protected $childFormOrder = [
        1 => 'termsAndConditions'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('termsAndConditions', 'checkbox', array(
                'label'=>'I confirm'
            ))

        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getHtmlTemplate(){
        return 'TermsAndConditions.html.twig';
    }

    /**
     * @return mixed|void
     */
    public function prepare(){
        $this->setEntity(TermsAndConditions::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }
}