<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step\WeekendAccommodation;

use Ice\FormBundle\Process\CourseRegistration\EventSubscriber\WeekendAccommodationSubscriber;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Ice\FormBundle\Process\CourseRegistration\Step\AbstractRegistrationStep;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WeekendAccommodationType extends AbstractRegistrationStep{
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
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->addEventSubscriber(new WeekendAccommodationSubscriber($builder->getFormFactory(), $this->getParentProcess()->getCourse()))
        ;

        parent::buildForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
        ));

        parent::setDefaultOptions($resolver);
    }

    /**
     * @return string
     */
    public function getTitle(){
        return 'Weekend accommodation';
    }

    public function getHtmlTemplate(){
        return 'WeekendAccommodation.html.twig';
    }

    public function getJavaScriptTemplate(){
        return 'WeekendAccommodation.js.twig';
    }

    public function prepare(){
        $this->setEntity(new WeekendAccommodation());
        $request = Request::createFromGlobals(); // FIXME: Passed as arg from processRequest
        // Terrible hack to deal with issue of binding making the stepReference hidden field null
        $request->query->set('stepReference', $this->getReference());
        $this->getForm()->bind($request);
        $this->setPrepared();
    }

    public function processRequest(Request $request = null)
    {
        // Request already bound in self::prepare
        parent::processRequest(null);

        $this->setComplete(false);
    }

    public function getAjaxResponse(array $array = array())
    {
        return new Response($this->renderHtml($array));
    }

    public function supportsAjaxResponse()
    {
        return true;
    }

}