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
}