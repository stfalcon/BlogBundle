<?php

namespace StfalconBundle\Bundle\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);

        // check don't display categories
        $this->assertEquals(0, $crawler->filter('table tbody tr')->count());
    }

    public function testCreateNewPost()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('admin_bundle_blog_post_create', array()));

        $inputs = $crawler->filter('form input');
        $inputs->first();
        $formId = str_replace("_slug", "", $inputs->current()->getAttribute('id'));

        $form = $crawler->selectButton('Создать и редактировать')->form();

        $form[$formId . '[title]'] = 'Post title';
        $form[$formId . '[slug]'] = 'post-slug';
        $form[$formId . '[text]'] = 'Post text';
        $form[$formId . '[tags]'] = 'Post,tags';
        $crawler = $client->submit($form);

        // check redirect to list of post
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_blog_post_edit', array('id' => 1) )));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);
        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("Post title")')->count());
    }

    public function testNotEmptyPostListForAdmin()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);

        // check display posts list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("My first post")')->count());
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

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $post = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));

        $crawler = $client->request('GET', $this->getUrl('admin_bundle_blog_post_edit', array('id' => $post->getId())));

        $inputs = $crawler->filter('form input');
        $inputs->first();
        $formId = str_replace("_slug", "", $inputs->current()->getAttribute('id'));

        $form = $crawler->selectButton('Сохранить')->form();

        $form[$formId . '[title]'] = 'New post title';
        $form[$formId . '[slug]'] = 'new-post-slug';
        $form[$formId . '[text]'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..';
        $form[$formId . '[tags]'] = 'php, symfony2, etc';
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_blog_post_edit', array('id' => $post->getId()) )));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("New post title")')->count());
    }

    public function testDeletePost()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $client = $this->makeClient(true);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $post = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));

        // delete category
        $crawler = $client->request('POST', $this->getUrl('admin_bundle_blog_post_delete', array('id' => $post->getId())), array('_method' => 'DELETE'));

        // check redirect to list of posts
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_blog_post_list', array())));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        // check notice
//        $this->assertTrue($client->getRequest()->getSession()->hasFlash('notice'));

        // check don't display deleting post
        $this->assertEquals(0, $crawler->filter('table tbody tr td:contains("post-about-php")')->count());
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

    public function testUploadValidImageInPost()
    {
        $file = tempnam(sys_get_temp_dir(), 'jenga.jpg');
        copy(realpath(__DIR__ . '/../Entity/Resources/files/posts/jenga.jpg'), $file);

        $photo = new UploadedFile($file, 'jenga.jpg', null, null, null, true);

        $client = $this->makeClient(true);
        $crawler = $client->request(
            'POST', $this->getUrl('blog_post_upload_image'), array(),
            array('form' => array('inlineUploadFile' => $photo))
        );

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("success")')->count());
    }

    public function testUploadInvalidImageInPost()
    {
        $invalidFile = tempnam(sys_get_temp_dir(), 'image.jpeg');
        copy(realpath(__DIR__ . '/../Entity/Resources/files/posts/image.php'), $invalidFile);

        $photo = new UploadedFile($invalidFile, 'image.jpeg', null, null, null, true);

        $client = $this->makeClient(true);
        $crawler = $client->request(
            'POST', $this->getUrl('blog_post_upload_image'), array(),
            array('form' => array('inlineUploadFile' => $photo)));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Your file is not valid")')->count());
    }
}