<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\DataTransformer\EntitiesToStringTransformer;

class TagsType extends AbstractType
{

    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->prependClientTransformer(new EntitiesToStringTransformer($this->registry->getEntityManager()));
    }

//        function getDefaultOptions(array $options)
////    public function getDefaultOptions(array $options)
//    {
//        var_dump($options);
//    }

    public function getParent(array $options)
    {
        return 'field';
    }

    public function getName()
    {
        return 'tags';
    }
}