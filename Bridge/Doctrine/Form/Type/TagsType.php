<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\DataTransformer\EntitiesToStringTransformer;

/**
 * Form type for tags
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagsType extends AbstractType
{

    protected $registry;

    /**
     * Constructor injection
     *
     * @param Symfony\Bundle\DoctrineBundle\Registry $registry Doctrine registry object
     *
     * @return void
     */
    public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilder $builder The form builder
     * @param array       $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->prependClientTransformer(
            new EntitiesToStringTransformer($this->registry->getEntityManager())
        );
    }

    /**
     * Returns the name of the parent type.
     *
     * @param array $options The options
     *
     * @return string
     */
    public function getParent(array $options)
    {
        return 'field';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tags';
    }
}