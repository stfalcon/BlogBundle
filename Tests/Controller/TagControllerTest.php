<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Controller;

//require_once(dirname(__FILE__).'/AbstractTestCase.php');
//use Stfalcon\Bundle\BlogBundle\Tests\Controller\AbstractTestCase;
//use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for TagController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagControllerTest extends AbstractTestCase
{

    public function testViewTag()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_tag_view', array('text' => 'doctrine2')), 'GET', true, true);

        // check display post title
        $this->assertEquals(1, $crawler->filter('div.post h3:contains("My first post")')->count());
        // check display post text
        $this->assertEquals(1, $crawler->filter('div.post:contains("In work we use Symfony2.")')->count());
        // check display link to post
        $url = $this->getUrl('blog_post_view', array('slug' => 'my-first-post'));
        $this->assertEquals(1, $crawler->filter('div.post h3 a[href="' . $url . '"]')->count());

        // check post tags
        $this->assertEquals(1, $crawler->filter('div.post ul.tags:contains("symfony2")')->count());
        $this->assertEquals(1, $crawler->filter('div.post ul.tags:contains("doctrine2")')->count());
    }

    public function testViewNotExistTag()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', $this->getUrl('blog_tag_view', array('text' => 'not-exist-tag')));

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testTagPagination()
    {
        $this->paginationCheck('blog_tag_view', 'php');
    }

}