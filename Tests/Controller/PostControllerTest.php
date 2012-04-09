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

    public function testEmptyPostsListForAdmin()
    {
        $this->loadFixtures(array());
        $crawler = $this->fetchCrawler($this->getUrl('blog_post_index', array()), 'GET', true, true);

        // check display notice
        $this->assertEquals(1, $crawler->filter('html:contains("List of posts is empty")')->count());
        // check don't display categories
        $this->assertEquals(0, $crawler->filter('ul li:contains("My first post")')->count());
    }

    public function testCreateNewPost()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('blog_post_create', array()));

        $form = $crawler->selectButton('Send')->form();

        $form['post[title]'] = 'Post title';
        $form['post[slug]'] = 'post-slug';
        $form['post[text]'] = 'Post text';
        $crawler = $client->submit($form);

        // check redirect to list of post
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('blog_post_index', array())));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        // check display new category in list
        $this->assertEquals(1, $crawler->filter('ul li:contains("Post title")')->count());
    }

    public function testNotEmptyPostListForAdmin()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_post_index', array()), 'GET', true, true);

        // check display posts list
        $this->assertEquals(1, $crawler->filter('ul li:contains("My first post")')->count());
    }

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

    public function testEditPost()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('blog_post_edit', array('slug' => 'my-first-post')));

        $form = $crawler->selectButton('Save')->form();

        $form['post[title]'] = 'New post title';
        $form['post[slug]'] = 'new-post-slug';
        $form['post[text]'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..';
        $form['post[tags]'] = 'php, symfony2, etc';
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('blog_post_index', array())));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $this->assertEquals(1, $crawler->filter('ul li:contains("New post title")')->count());
    }

    public function testDeletePost()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $client = $this->makeClient(true);
        // delete post
        $crawler = $client->request('GET', $this->getUrl('blog_post_delete', array('slug' => 'my-first-post')));
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

    public function _testUploadValidImageInPost()
    {

    }

    public function _testUploadInvalidImageInPost()
    {

    }
}