<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($em)
    {
        // posts
        $firstpost = new Post();
        $firstpost->setTitle('My first post');
        $firstpost->setSlug('my-first-post');
        $firstpost->setText('In work we use Symfony2.<!--more-->And text after cut');
        
        $em->persist($firstpost);
        $em->flush();

        $this->addReference('post-first', $firstpost);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}