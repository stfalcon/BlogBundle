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
     * Post id
     *
     * @var integer $id
     * @orm:Column(name="id", type="integer")
     * @orm:Id
     * @orm:GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @assert:NotBlank()
     * @orm:Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * Set post id
     *
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get post id
     *
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get post title
     *
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

}