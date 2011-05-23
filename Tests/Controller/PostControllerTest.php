<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testEmptyPostsList()
    {
        $this->loadFixtures(array());
        $crawler = $this->fetchCrawler($this->getUrl('blogPostIndex', array()), 'GET', true, true);

        // check display notice
        $this->assertEquals(1, $crawler->filter('html:contains("List of posts is empty")')->count());
        // check don't display categories
        $this->assertEquals(0, $crawler->filter('ul li:contains("My first post")')->count());
    }

    public function testCreateNewPost()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('blogPostCreate', array()));

        $form = $crawler->selectButton('Send')->form();

        $form['post[title]'] = 'Post title';
        $form['post[text]'] = 'Post text';
        $crawler = $client->submit($form);

        // check redirect to list of post
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertTrue($client->getResponse()->isRedirected($this->getUrl('blogPostIndex', array())));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        // check display new category in list
        $this->assertEquals(1, $crawler->filter('ul li:contains("Post title")')->count());
    }

    public function testPostList()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blogPostIndex', array()), 'GET', true, true);

        // check display categories list
        $this->assertEquals(1, $crawler->filter('ul li:contains("My first post")')->count());
    }

    public function testViewPost()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blogPostView', array('id' => 1)), 'GET', true, true);

//        // check display categories list
//        $this->assertEquals(1, $crawler->filter('ul li:contains("My first post")')->count());
    }
}