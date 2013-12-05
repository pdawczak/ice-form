<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\PassportPhoto;

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

class PassportPhotoType extends AbstractRegistrationStep
{
    /**
     * @var array
     */
    protected $childFormOrder = [
        1 => 'photoMethod'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photoMethod', 'choice', array(
                'label' => 'Please indicate how you intend to send your photographs.',
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'choices' => array(
                    'Email' => 'I agree to email my photographs',
                    'Post' => 'I agree to post my photographs',
                ),
                'constraints' => array(
                    new NotBlank()
                )
            ));
        parent::buildForm($builder, $options);
    }

    public function getHtmlTemplate()
    {
        return 'PassportPhoto.html.twig';
    }

    public function getJavascriptTemplate()
    {
        return 'PassportPhoto.js.twig';
    }

    public function prepare()
    {
        $this->setEntity(PassportPhoto::fromStepProgress($this->getStepProgress()));
        $this->setPrepared();
    }
}
