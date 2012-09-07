<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * PostController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostController extends Controller
{

    /**
     * List of posts for admin
     *
     * @param int      $page     Page number
     *
     * @return array
     * @Route("/blog/{page}", name="blog", requirements={"page" = "\d+"}, defaults={"page" = "1"} )
     * @Template()
     */
    public function indexAction($page)
    {
        $allPosts = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPosts();

        $pageRange = $this->container->getParameter('page_range');

         $posts= $this->get('knp_paginator')->paginate($allPosts, $page, $pageRange);

        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Блог')->setCurrent(true);
        }

        return array('posts' => $posts);
    }

    /**
     * View post
     *
     * @param Post $post
     *
     * @return array
     * @Route("/blog/post/{slug}", name="blog_post_view")
     * @Template()
     */
    public function viewAction(Post $post)
    {
        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', array('route' => 'blog'));
            $breadcrumbs->addChild($post->getTitle())->setCurrent(true);
        }

        return array(
            'post' => $post,
        );
    }

    /**
     * RSS feed
     *
     * @return Response
     * @Route("/blog/rss", name="blog_rss")
     */
    public function rssAction()
    {
        $feed = new \Zend\Feed\Writer\Feed();

        $config = $this->container->getParameter('stfalcon_blog.config');

        $feed->setTitle($config['rss']['title']);
        $feed->setDescription($config['rss']['description']);
        $feed->setLink($this->generateUrl('blog_rss', array(), true));

        $posts = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPosts();
        foreach ($posts as $post) {
            $entry = new \Zend\Feed\Writer\Entry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($this->generateUrl('blog_post_view', array('slug' => $post->getSlug()), true));

            $feed->addEntry($entry);
        }

        return new Response($feed->export('rss'));
    }

    /**
     * Show last blog posts
     *
     * @param int $count A count of posts
     *
     * @return array()
     * @Template()
     */
    public function lastAction($count = 1)
    {
        $posts = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconBlogBundle:Post")->getLastPosts($count);

        return array('posts' => $posts);
    }
}