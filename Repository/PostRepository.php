<?php

namespace Stfalcon\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostRepository extends EntityRepository
{

    /**
     * Get all posts
     *
     * @return array
     */
    public function getAllPosts()
    {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM StfalconBlogBundle:Post p');

        return $query->getResult();
    }
}