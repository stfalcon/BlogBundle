<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Post
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @orm:Table(name="blog_posts")
 */
class Post
{
    /**
     * @var integer $id
     *
     * @orm:Column(name="id", type="integer")
     * @orm:Id
     * @orm:GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get post id
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post id
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}