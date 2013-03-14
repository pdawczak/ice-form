<?php

namespace Ice\FormBundle\Process\CourseRegistration\Step;

use Symfony\Component\Form\FormBuilderInterface;

class Accommodation extends AbstractRegistrationStep{
    public function processSubmission(){

    }
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('text');
    }

    public function isComplete(){

    }
}