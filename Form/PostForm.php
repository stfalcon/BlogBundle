<?php

namespace Stfalcon\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Post form
 */
class PostForm extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('title')
                ->add('slug')
                ->add('text')
                ->add('tags', 'tags');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Stfalcon\Bundle\BlogBundle\Entity\Post',
        );
    }
}