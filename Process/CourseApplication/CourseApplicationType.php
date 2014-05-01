<?php

namespace Ice\FormBundle\Process\CourseApplication;

use Ice\FormBundle\Form\Builder\FormBuilderInterface;
use Ice\FormBundle\Form\Options\FormOptionsConfigurationInterface;
use Ice\FormBundle\Form\Type\FormTypeInterface;
use Ice\FormBundle\Process\CourseApplication\StepInterface;

class CourseApplicationType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var StepInterface $step */
        $step = $options['step'];

        $builder
            ->add('stepReference', 'hidden', array(
                'data' => $step->getReference(),
                'mapped' => false
            ))
            ->add('continue', 'hidden', array(
                'mapped' => false
            ))
            //->add('test', 'text', ['constraints'=>['length' => ['min'=>3]]])
            ->add(
                $step->getReference(),
                $step->getFormType(),
                array_merge(['label'=> ' '], $step->getFormOptions())
            )
            /*->addEventListener(FormEvents::PRE_BIND, function (FormEvent $e) use ($step) {
                $data = $e->getData();
                $data['stepReference'] = $step->getReference();
                if (isset($data['continue'])) {
                    unset($data['continue']);
                    $this->continueClicked = true;
                    $e->setData($data);
                }
            }, 1)*/
        ;
    }

    public function configureOptions(FormOptionsConfigurationInterface $optionConfiguration)
    {
        $optionConfiguration->setRequired(['step']);
    }


    public function getName()
    {
        return '';
    }
}
