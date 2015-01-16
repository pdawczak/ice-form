<?php
namespace Ice\FormBundle\Process\CourseRegistration\Step\CurrentOccupation;

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

class CurrentOccupationType extends AbstractRegistrationStep
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentOccupation', 'text', array('label' => 'What is your current occupation?', 'required'=>false))
            ->add('companyOrInstitution', 'text', array('label' => 'Name of company or place of study', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionAddress1', 'text', array('label' => 'Address line 1', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionAddress2', 'text', array('label' => 'Address line 2', 'required' => false))
            ->add('institutionAddress3', 'text', array('label' => 'Address line 3', 'required' => false))
            ->add('institutionTown', 'text', array('label' => 'Town', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionCounty', 'text', array('label' => 'County/province/state', 'required' => false))
            ->add('institutionPostCode', 'text', array('label' => 'Postal code', 'required' => true, 'constraints' => array(new NotBlank())))
            ->add('institutionCountry', 'country', array('label' => 'Country', 'required' => true, 'constraints' => array(new NotBlank())))
        ;
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Current occupation';
    }

    /**
     * @param Request $request
     */
    public function processRequest(Request $request = null)
    {
        $this->getForm()->bind($request);
        /** @var $entity CurrentOccupation */
        $entity = $this->getEntity();

        foreach (array(
                     1 => 'currentOccupation',
                     2 => 'companyOrInstitution',
                     3 => 'institutionAddress1',
                     4 => 'institutionAddress2',
                     5 => 'institutionAddress3',
                     6 => 'institutionTown',
                     7 => 'institutionCounty',
                     8 => 'institutionPostCode',
                     9 => 'institutionCountry',
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
            $contact = CurrentOccupation::fromStepProgress($this->getStepProgress());
        } else {
            $contact = new CurrentOccupation();
        }
        $this->setEntity($contact);
        $this->setPrepared();
    }

    public function getHtmlTemplate()
    {
        return 'CurrentOccupation.html.twig';
    }
}
