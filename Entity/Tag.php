<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Tag
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @orm:Table(name="blog_tags")
 * @orm:Entity
 */
class Tag
{
    /**
     * Tag id
     *
     * @var integer $id
     * @orm:Column(name="id", type="integer")
     * @orm:Id
     * @orm:GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Tag text
     * 
     * @var text $text
     * @assert:NotBlank()
     * @orm:Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * Entity constructor
     *
     * @param string $text
     * @return void
     */
    public function  __construct($text = null)
    {
        $this->text = $text;
    }

    /**
     * Set Tag id
     *
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Tag id
     *
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Tag text
     *
     * @return null|string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set Tag text
     *
     * @param string $text
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

}