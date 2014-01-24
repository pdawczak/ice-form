<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\ConfirmNotCommonLaw;

use Ice\JanusClientBundle\Exception\ValidationException;
use JMS\Serializer\Tests\Fixtures\Person;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ice\JanusClientBundle\Entity\User;

class ConfirmNotCommonLawType extends AbstractRegistrationStep
{
    public function getTitle()
    {
        return 'Legal jurisdiction';
    }

    /**
     * @var array confirmation value
     */
    protected $childFormOrder = [
        1 => 'confirmNotCommonLaw'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('confirmNotCommonLaw', 'checkbox', array(
                'label'=>'I confirm that my legal jurisdiction is not based on English Common Law.'
            ))

        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @return mixed|void
     */
    public function prepare(){
        $this->setEntity(ConfirmNotCommonLaw::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }

    public function getHtmlTemplate()
    {
        return 'ConfirmNotCommonLaw.html.twig';
    }
}