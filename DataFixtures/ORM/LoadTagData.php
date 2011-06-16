<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($em)
    {
        $symfony2 = new Tag('symfony2');
        $em->persist($symfony2);

        $doctrine2 = new Tag('doctrine2');
        $em->persist($doctrine2);

        $php = new Tag('php');
        $em->persist($php);

        $em->flush();
        
        $this->addReference('tag-php', $php);
        $this->addReference('tag-doctrine2', $doctrine2);
        $this->addReference('tag-symfony2', $symfony2);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}