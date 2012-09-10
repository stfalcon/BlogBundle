<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Controller;

//use Stfalcon\Bundle\BlogBundle\Tests\Controller\AbstractTestCase;
use Application\Bundle\DefaultBundle\Tests\Controller\AbstractTestCase;
//use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for PostController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostControllerTest extends AbstractTestCase
{

    public function testViewPost()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_post_view', array('slug' => 'my-first-post')), 'GET', true, true);

        // check display post title
        $this->assertEquals(1, $crawler->filter('div.post h1:contains("My first post")')->count());
        // check display post text
        $this->assertEquals(1, $crawler->filter('div.post:contains("In work we use Symfony2.")')->count());
        // and find <span id="more">
        $this->assertEquals(1, $crawler->filter('div.post span#more')->count());

        // check post tags
        $this->assertEquals(1, $crawler->filter('div.post ul.tags:contains("symfony2")')->count());
        $this->assertEquals(1, $crawler->filter('div.post ul.tags:contains("doctrine2")')->count());
    }

    public function testViewNotExistPost()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', $this->getUrl('blog_post_view', array('slug' => 'not-exist-post')));

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testPostListForUser()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog', array()), 'GET', true, true);

        // check post count
        $this->assertEquals(2, $crawler->filter('div.post')->count());

        $firstUrl = $this->getUrl('blog_post_view', array('slug' => 'my-first-post'));
        $secondUrl = $this->getUrl('blog_post_view', array('slug' => 'post-about-php'));

        // check links to posts
        $this->assertEquals(1, $crawler->filter('div.post h3 a[href="' . $firstUrl . '"]')->count());
        $this->assertEquals(1, $crawler->filter('div.post h3 a[href="' . $secondUrl . '"]')->count());

        // check exist read more tag
        $this->assertEquals(0, $crawler->filter('div.post:contains("<!--more-->")')->count());

        // check link to read more
        $this->assertEquals(1, $crawler->filter('div.post a[href="' . $firstUrl . '#more"]')->count());
        $this->assertEquals(0, $crawler->filter('div.post a[href="' . $secondUrl . '#more"]')->count());

        // check exist posts tags
        $this->assertEquals(1, $crawler->filter('div.post ul.tags:contains("php")')->count());
        $this->assertEquals(1, $crawler->filter('div.post ul.tags:contains("symfony2")')->count());
        $this->assertEquals(1, $crawler->filter('div.post ul.tags:contains("doctrine2")')->count());

        // check links to posts commets
        $this->assertEquals(1, $crawler->filter('div.post a[href="' . $firstUrl . '#disqus_thread"]')->count());
        $this->assertEquals(1, $crawler->filter('div.post a[href="' . $secondUrl . '#disqus_thread"]')->count());
    }

    public function testBlogPagination()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostPaginatorData'));

        $this->paginationCheck('blog', '', '', 'post', 10);
    }

}