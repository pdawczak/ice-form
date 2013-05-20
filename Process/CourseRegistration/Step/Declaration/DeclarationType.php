<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\Declaration;

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

class DeclarationType extends AbstractRegistrationStep{

    protected $childFormOrder = [
        1 => 'qualificationDeclaration'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('qualificationDeclaration', 'checkbox', array(
                'label'=>'I confirm my understanding'
            ))

        ;
        parent::buildForm($builder, $options);
    }

    public function getHtmlTemplate(){
        return 'Declaration.html.twig';
    }

    public function prepare(){
        $this->setEntity(Declaration::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }
}