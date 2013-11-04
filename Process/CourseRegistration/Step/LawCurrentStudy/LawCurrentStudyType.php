<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LawCurrentStudy;

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

class LawCurrentStudyType extends AbstractRegistrationStep
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('llbEnrolledWithUniversityOfLondon', 'choice', array(
                    'choices'=>array(
                        'Y'=>'Yes',
                        'N'=>'No'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'Are you currently enrolled on the LLB/Diploma in Law programme with the University of London International Programme?'
                )
            )
            ->add('quAltUni', 'text', array('label' => 'Please specify the University you are studying with', 'required'=>false))
        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Current study';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null)
    {
        $this->getForm()->bind($request);
        /** @var $entity LawCurrentStudy */
        $entity = $this->getEntity();

        foreach (array(
                     1 => 'llbEnrolledWithUniversityOfLondon',
                     2 => 'quAltUni'
                 )
                 as $order => $fieldName) {
            $getter = 'get' . ucfirst($fieldName);
            $value = $entity->$getter();

            $this->getStepProgress()->setFieldValue(
                $fieldName,
                $order,
                $this->getForm()->get($fieldName)->getConfig()->getOption('label'),
                $value
            );
        }

        if ($this->getForm()->isValid()) {
            $this->setComplete();
        } else {
            $this->setComplete(false);
        }
        $this->setUpdated();
        $this->save();
    }

    /**
     * @return mixed|void
     */
    public function prepare()
    {
        if ($this->getStepProgress()->getUpdated()) {
            $contact = LawCurrentStudy::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new LawCurrentStudy();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getHtmlTemplate()
    {
        return 'LawCurrentStudy.html.twig';
    }

    public function getJavaScriptTemplate(){
        return 'LawCurrentStudy.js.twig';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                /** @var $data LawCurrentStudy */
                $data = $form->getData();
                $groups = array('default');
                if($data->getLlbEnrolledWithUniversityOfLondon() === 'N'){
                    $groups[] = 'not_uol';
                }
                return $groups;
            }
        ));
    }
}
