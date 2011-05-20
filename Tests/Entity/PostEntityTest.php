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
        $title = "First blog post";

        $post = new Post();
        $post->setTitle($title);

        $this->assertEquals($post->getTitle(), $title);
    }
}