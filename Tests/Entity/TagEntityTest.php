<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Entity;

use Stfalcon\Bundle\BlogBundle\Entity\Tag;

/**
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagEntityTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyTagIdIsNull()
    {
        $Tag = new Tag();
        $this->assertNull($Tag->getId());
    }

    public function testSetAndGetTagText()
    {
        $text = "symfony2";

        $Tag = new Tag();
        $Tag->setText($text);

        $this->assertEquals($Tag->getText(), $text);
    }
}