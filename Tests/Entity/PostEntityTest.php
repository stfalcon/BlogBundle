<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Entity;

use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostEntityTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyPostIdIsNull()
    {
        $post = new Post();
        $this->assertNull($post->getId());
    }

    public function testSetAndGetPostTitle()
    {
        $title = "A week of symfony #228 (9->15 May 2011)";

        $post = new Post();
        $post->setTitle($title);

        $this->assertEquals($post->getTitle(), $title);
    }

    public function testSetAndGetPostText()
    {
        $text = "This week, Symfony2 reintroduced parameters in the DIC of several bundles, error page template customization was greatly simplified and Assetic introduced configuration for automatically apply filters to assets based on path.";

        $post = new Post();
        $post->setText($text);

        $this->assertEquals($post->getText(), $text);
    }
}