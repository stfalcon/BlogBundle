<?php


// HelloBundle/DependencyInjection/HelloExtension.php
namespace Stfalcon\Bundle\BlogBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class StfalconBlogExtension extends Extension {

    public function load(array $config, ContainerBuilder $container) {
        $definition = new Definition('Stfalcon\Bundle\BlogBundle\Extension\ReadMoreTwigExtension');
        $definition->addTag('twig.extension');
        $container->setDefinition('read_more', $definition);
    }

}