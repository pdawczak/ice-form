<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\LawCurrentStatus;

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

class LawCurrentStatusType extends AbstractRegistrationStep
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentStatus', 'choice', array(
                    'choices'=>array(
                        'Professional'=>'Lawyer',
                        'Student'=>'Law student',
                        'Other'=>'Other'
                    ),
                    'expanded'=>true,
                    'multiple'=>false,
                    'label'=>'What is your current status?'
                )
            )
            ->add('specifyOther', 'text', array('label' => 'Please specify', 'required'=>false))

            ->add('companyOrInstitution', 'text', array('label' => 'Name of company or institution', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionAddress1', 'text', array('label' => 'Address line 1', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionAddress2', 'text', array('label' => 'Address line 2', 'required' => false))
            ->add('institutionAddress3', 'text', array('label' => 'Address line 3', 'required' => false))
            ->add('institutionTown', 'text', array('label' => 'Town', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionCounty', 'text', array('label' => 'County/province/state', 'required' => false))
            ->add('institutionPostCode', 'text', array('label' => 'Postal code', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionCountry', 'country', array('label' => 'Country', 'required' => true, 'constraints' => array(new NotBlank())))

            ->add('currentQualifications', 'textarea', array('label' => 'Please state any current Law qualifications held', 'required'=>false))

            ->add('courseTitle', 'text', array('label' => 'Title of course', 'required'=>false))
            ->add('courseStartDate', 'date', array(                    'label' => 'Course start date',
                'input' => "datetime",
                'widget' => "choice",
                'format' => 'd MMM yyyy',
                'required' => false,
                'empty_value' => array(
                    'day' => 'Day',
                    'month' => 'Month',
                    'year' => 'Year',
                )))
            ->add('oneYearOfStudy', 'checkbox', array(
                'label' => 'I confirm that I will have completed a minimum of one year of legal studies at undergraduate level by the start date of this summer school.',
                'required' => false
            ))

        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Current status';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null)
    {
        $this->getForm()->bind($request);
        /** @var $entity LawCurrentStatus */
        $entity = $this->getEntity();

        foreach (array(
                     1 => 'currentStatus',
                     2 => 'specifyOther',
                     3 => 'currentQualifications',
                     4 => 'companyOrInstitution',
                     5 => 'institutionAddress1',
                     6 => 'institutionAddress2',
                     7 => 'institutionAddress3',
                     8 => 'institutionTown',
                     9 => 'institutionCounty',
                     10 => 'institutionPostCode',
                     11 => 'institutionCountry',
                     12 => 'courseTitle',
                     13 => 'courseStartDate',
                     14 => 'oneYearOfStudy'
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
            $contact = LawCurrentStatus::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new LawCurrentStatus();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getHtmlTemplate()
    {
        return 'LawCurrentStatus.html.twig';
    }

    public function getJavaScriptTemplate(){
        return 'LawCurrentStatus.js.twig';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                /** @var $data LawCurrentStatus */
                $data = $form->getData();
                $groups = array('default');
                if($data->getCurrentStatus() === 'Student'){
                    $groups[] = 'status_student';
                }
                if($data->getCurrentStatus() === 'Other'){
                    $groups[] = 'status_other';
                }
                return $groups;
            }
        ));
    }
}
