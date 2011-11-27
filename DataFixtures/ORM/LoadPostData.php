<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * Posts fixtures
 */
class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load posts fixtures to database
     *
     * @param Doctrine\ORM\EntityManager $em Entity manager object
     *
     * @return void
     */
    public function load($em)
    {
        // posts
        $postfirst = new Post();
        $postfirst->setTitle('My first post');
        $postfirst->setSlug('my-first-post');
        $postfirst->setText('In work we use Symfony2.<!--more-->And text after cut');
        $postfirst->addTag($em->merge($this->getReference('tag-symfony2')));
        $postfirst->addTag($em->merge($this->getReference('tag-doctrine2')));
        $em->persist($postfirst);

        $postaboutphp = new Post();
        $postaboutphp->setTitle('Post about php');
        $postaboutphp->setSlug('post-about-php');
        $postaboutphp->setText('The PHP development team would like to announce the immediate availability of PHP 5.3.6.');
        $postaboutphp->addTag($em->merge($this->getReference('tag-php')));
        $em->persist($postaboutphp);

        $em->flush();

        $this->addReference('post-first', $postfirst);
        $this->addReference('post-about-php', $postaboutphp);
    }

    /**
     * Get the number for sorting fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }

}