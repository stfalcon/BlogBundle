<?php

namespace Stfalcon\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Post form
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostForm extends AbstractType
{

    /**
     * Builds the form
     *
     * @param FormBuilder $builder The form builder
     * @param array       $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('title')
                ->add('slug')
                ->add('text')
                ->add('tags', 'tags');
    }

    /**
     * Returns the default options for this type.
     *
     * @return array The default options
     */
    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Stfalcon\Bundle\BlogBundle\Entity\Post',
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'post';
    }
}