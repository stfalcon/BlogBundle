<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Tag
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @ORM\Table(name="blog_tags")
 * @ORM\Entity
 */
class Tag
{
    /**
     * Tag id
     *
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Tag text
     * 
     * @var text $text
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Post", mappedBy="tags")
     */
    private $posts;

    /**
     * Entity constructor
     *
     * @param string $text
     * @return void
     */
    public function  __construct($text = null)
    {
        $this->text = $text;
        $this->posts = new ArrayCollection();
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

    public function getPosts()
    {
        return $this->posts;
    }

}