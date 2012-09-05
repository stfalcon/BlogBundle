<?php

namespace StfalconBundle\Bundle\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for PostController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostControllerTest extends WebTestCase
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
        $client = static::createClient();

        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostPaginatorData'
        ));
        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 1)), 'GET'
        );
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("1")')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());

        $this->assertEquals(5, $crawler->filter('.pagination span')->count());

        // test second page
        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 2)), 'GET', true, true
        );
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("2")')->count());

        $this->assertEquals(7, $crawler->filter('.pagination span')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());

        // @todo refact start
        $link5 = $crawler->filter('span.next a')->link();
        $crawler = $client->click($link5);
        $this->assertEquals(3, $client->getRequest()->attributes->get('page'));

        $link2 = $crawler->selectLink('1')->link();
        $crawler = $client->click($link2);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 3)), 'GET', true, true
        );
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("3")')->count());

        $this->assertEquals(5, $crawler->filter('.pagination span')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());
        $link3 = $crawler->selectLink('2')->link();
        $crawler = $client->click($link3);
        $this->assertEquals(2, $client->getRequest()->attributes->get('page'));

        $link6 = $crawler->filter('span.previous a')->link();
        $crawler = $client->click($link6);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $link7 = $crawler->filter('span.last a')->link();
        $crawler = $client->click($link7);
        $this->assertEquals(3, $client->getRequest()->attributes->get('page'));

        $link8 = $crawler->filter('span.first a')->link();
        $crawler = $client->click($link8);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 1)), 'GET', true, true
        );
        $this->assertEquals(0, $crawler->filter('span.first a')->count());
        $this->assertEquals(0, $crawler->filter('span.previous a')->count());

        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 3)), 'GET', true, true
        );
        $this->assertEquals(0, $crawler->filter('span.last a')->count());
        $this->assertEquals(0, $crawler->filter('span.next a')->count());
        // @todo refact end
     }

}