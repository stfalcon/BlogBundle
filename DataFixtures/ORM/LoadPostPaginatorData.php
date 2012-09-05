<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * Posts fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadPostPaginatorData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load posts fixtures to database
     *
     * @param Doctrine\ORM\EntityManager $manager Entity manager object
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i<30; $i++)
        {
                $postforpaginator = new Post();
                $postforpaginator->setTitle('Post for paginator '.$i);
                $postforpaginator->setSlug('post-for-paginator-'.$i);
                $postforpaginator->setText('Generally this bundle is based on Knp Pager component. This component introduces a different way for pagination handling. You can read more about the internal logic on the given documentation link.'.$i);
                $manager->persist($postforpaginator);
        }

        $manager->flush();
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