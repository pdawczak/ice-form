<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Ice\FormBundle\Process\CourseRegistration\EventSubscriber\WeekendAccommodationSubscriber;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ice\MinervaClientBundle\Entity\StepProgress;

class WeekendAccommodationType extends AbstractRegistrationStep
{
    protected $childFormOrder = array(
        1 => 'accommodation',
        2 => 'accommodationSharingWith',
        4 => 'adaptedBedroomRequired',
        5 => 'accommodationRequirements',
        6 => 'bedAndBreakfastAccommodation',
        7 => 'platter',
        8 => 'platterOption',
    );

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new WeekendAccommodationSubscriber($builder->getFormFactory(), $this->getParentProcess()->getCourse()));

        parent::buildForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
        ));
        $resolver->setDefaults(array(
            'validation_groups' => function (FormInterface $form) {
                $groups = [];
                if ($this->isContinueClicked()) {
                    $groups[] = 'Default';
                } else {
                    $groups[] = 'no_validate';
                }
                return $groups;
            }
        ));

        parent::setDefaultOptions($resolver);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Weekend accommodation';
    }

    public function getHtmlTemplate()
    {
        return 'WeekendAccommodation.html.twig';
    }

    public function getJavaScriptTemplate()
    {
        return 'WeekendAccommodation.js.twig';
    }

    public function prepare()
    {
        if ($this->getStepProgress()) {
            $entity = WeekendAccommodation::fromStepProgress($this->getStepProgress());
        }
        else{
            $entity = new WeekendAccommodation();
        }
        $this->setEntity($entity);
        $this->setPrepared();
    }

    public function processAjaxRequest(Request $request)
    {
        $this->prepare();
        $this->getForm()->bind($request);
        $this->getParentProcess()->setAjaxResponse(new Response($this->renderHtml()));
    }

    public function supportsAjaxResponse()
    {
        return true;
    }

    /**
     * Gets the description for a given field.
     *
     * @param $fieldName
     * @return string
     */
    protected function getFieldDescription($fieldName)
    {
        switch ($fieldName) {
            case 'adaptedBedroomRequired':
                return 'Adapted bedroom required?';
            case 'accommodationRequirements':
                return 'Accommodation Requirements';
        }
        return parent::getFieldDescription($fieldName);
    }


}