<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Form\PostForm;

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
     * @return array
     * @Route("/blog", name="blog")
     * @Template()
     */
    public function indexAction()
    {
        $posts = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPosts();

        if ($this->has('menu.breadcrumbs')) {
            $breadcrumbs = $this->get('menu.breadcrumbs');
            $breadcrumbs->addChild('Блог')->setIsCurrent(true);
        }

        return array('posts' => $posts);
    }

    /**
     * Projects list
     *
     * @return array
     * @Route("/admin/blog/posts", name="blog_post_index")
     * @Template()
     */
    public function listAction()
    {
        $posts = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPosts();

        return array('posts' => $posts);
    }

    /**
     * Create new post
     *
     * @return array|RedirectResponse
     * @Route("/admin/blog/post/create", name="blog_post_create")
     * @Template()
     */
    public function createAction()
    {
        $post = new Post();
        $form = $this->get('form.factory')->create(new PostForm(), $post);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($post);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice',
                    'Congratulations, your post is successfully created!'
                );
                return new RedirectResponse($this->generateUrl('blog_post_index'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * View post
     *
     * @param string $slug Post slug
     *
     * @return array
     * @Route("/blog/post/{slug}", name="blog_post_view")
     * @Template()
     */
    public function viewAction($slug)
    {
        $post = $this->_findPostBySlug($slug);

//        if ($this->has('menu.main')) {
//            $menu = $this->get('menu.main');
//            $menu->getChild('Блог')->setIsCurrent(true);
//        }

        if ($this->has('menu.breadcrumbs')) {
            $breadcrumbs = $this->get('menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', $this->get('router')->generate('blog'));
            $breadcrumbs->addChild($post->getTitle())->setIsCurrent(true);
        }

        return array(
            'post' => $post,
        );
    }

    /**
     * Edit post
     *
     * @param string $slug Post slug
     *
     * @return RedirectResponse
     * @Route("/admin/blog/post/edit/{slug}", name="blog_post_edit")
     * @Template()
     */
    public function editAction($slug)
    {
        $post = $this->_findPostBySlug($slug);
        $form = $this->get('form.factory')->create(new PostForm(), $post);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // save project
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($post);

                $em->flush();

                $this->get('request')->getSession()->setFlash('notice',
                    'Congratulations, your post is successfully updated!'
                );
                return new RedirectResponse($this->generateUrl('blog_post_index'));
            }
        }

        return array('form' => $form->createView(), 'post' => $post);
    }

    /**
     * Delete post
     *
     * @param string $slug Post slug
     *
     * @return RedirectResponse
     * @Route("/admin/blog/post/delete/{slug}", name="blog_post_delete")
     */
    public function deleteAction($slug)
    {
        $post = $this->_findPostBySlug($slug);

        $em = $this->get('doctrine')->getEntityManager();
        $em->remove($post);
        $em->flush();

        $this->get('request')->getSession()->setFlash('notice', 'Your post is successfully delete.');
        return new RedirectResponse($this->generateUrl('blog_post_index'));
    }

    /**
     * Try find post by slug
     *
     * @param string $slug Post slug
     *
     * @return Category
     */
    private function _findPostBySlug($slug)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $post = $em->getRepository("StfalconBlogBundle:Post")
                ->findOneBy(array('slug' => $slug));
        if (!$post) {
            throw new NotFoundHttpException('The post does not exist.');
        }

        return $post;
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