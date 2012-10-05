<?php

namespace Symfio\WebsiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('owner', 'text', array('error_bubbling' => true));
        $builder->add('repo', 'text', array('error_bubbling' => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Symfio\\WebsiteBundle\\Entity\\Project',
        ));
    }

    public function getName()
    {
        return 'project';
    }
}
