<?php

namespace Stfalcon\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * MarkItUp Upload Form
 */
class MarkItUpUploadForm extends AbstractType
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
        $builder->add('inlineUploadFile', 'file');
    }

    /**
     * Returns the default options for this type.
     *
     * @param array $options The options
     *
     * @return array The default options
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Stfalcon\Bundle\BlogBundle\Entity\UploadedFile',
            'csrf_protection' => false,
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'uploadForm';
    }
}