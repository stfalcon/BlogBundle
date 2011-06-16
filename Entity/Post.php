<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Post
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * Post id
     *
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Post title
     * 
     * @var string $title
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $slug
     *
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Post text
     * 
     * @var text $text
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * Post text as HTML code
     *
     * @var text $textAsHtml
     * @ORM\Column(name="text_as_html", type="text")
     */
    private $textAsHtml;

    /**
     * Tags for post
     * 
     * @var ArrayCollection
     * @Assert\NotBlank()
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Tag")
     * @ORM\JoinTable(name="blog_posts_tags",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
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
     * Get post slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set post slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
        $this->setTextAsHtml($text);
    }

    private function setTextAsHtml($text)
    {
        // update text html code
        require_once __DIR__ . '/../Resources/vendor/geshi/geshi.php';

        $text = preg_replace_callback(
                    '/<pre lang="(.*?)">\r?\n?(.*?)\r?\n?\<\/pre>/is',
                    /**
                     * @param string $data
                     * @return string
                     */
                    function($data) {
                        $geshi = new \GeSHi($data[2], $data[1]);
                        return $geshi->parse_code();
                    }, $text);

        $this->textAsHtml = $text;
    }

    /**
     * Get post text as HTML code
     * @return string
     */
    public function getTextAsHtml()
    {
        return $this->textAsHtml;
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

    public function setTags(\Doctrine\Common\Collections\Collection $tags)
    {
        $this->tags = $tags;
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