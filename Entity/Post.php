<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Post
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @orm:Table(name="blog_posts")
 * @orm:Entity
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
     * Post title
     * 
     * @var string $title
     * @assert:NotBlank()
     * @orm:Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * Post text
     * 
     * @var text $text
     * @assert:NotBlank()
     * @orm:Column(name="text", type="text")
     */
    private $text;

    /**
     * Tags for post
     * 
     * @var ArrayCollection
     * @orm:ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Tag")
     * @orm:JoinTable(name="blog_posts_tags",
     *      joinColumns={@orm:JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@orm:JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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

    /**
     * Get post text
     *
     * @return null|string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set post text
     *
     * @param string $text
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Add tag to post
     *
     * @param Tag $tag
     * @return void
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Get all tags
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

}