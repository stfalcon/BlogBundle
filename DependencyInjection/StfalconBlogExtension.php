<?php

namespace Stfalcon\Bundle\BlogBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class StfalconBlogExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
        $config = array();
        foreach($configs as $c) {
            $config = array_merge($config, $c);
        }

        $container->setParameter('stfalcon_blog.config', $config);
        
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('service.xml');
    }

}