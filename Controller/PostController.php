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

        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Блог')->setCurrent(true);
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
     * Edit post
     *
     * @param Post $post
     *
     * @return RedirectResponse
     * @Route("/admin/blog/post/edit/{slug}", name="blog_post_edit")
     * @Template()
     */
    public function editAction(Post $post)
    {
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
     * @param Post $post
     *
     * @return RedirectResponse
     * @Route("/admin/blog/post/delete/{slug}", name="blog_post_delete")
     */
    public function deleteAction($post)
    {
        $em = $this->get('doctrine')->getEntityManager();
        $em->remove($post);
        $em->flush();

        $this->get('request')->getSession()->setFlash('notice', 'Your post is successfully delete.');
        return new RedirectResponse($this->generateUrl('blog_post_index'));
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


    /**
     * Upload photo
     *
     * @return string
     * @Route("/admin/blog/uploadImage", name="blog_post_upload_image")
     */
    public function uploadImageAction()
    {
        $file = $this->getRequest()->files->get('inline_upload_file');
        if ($file && $file->isValid()) {
            $pathinfo = pathinfo($file->getClientOriginalName());
            $ext = strtolower($pathinfo['extension']);
            if (in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {
                $uploadDir = realpath($this->get('kernel')->getRootDir() . '/../web/uploads/images');
                $newName = uniqid() . '.' . $ext;
                $file->move($uploadDir, $newName);
                $info = getImageSize($uploadDir . '/' . $newName);
                $resopnse = array(
                    'status' => 'success',
                    'src' => '/uploads/images/' . $newName,
                    'width' => $info[0],
                    'height' => $info[1],
                );
            } else {
                $resopnse = array(
                    'msg' => 'File extension is not valid!',
                );
            }
        } else {
            $resopnse = array(
                'msg' => 'Please, select proper file!',
            );
        }

        return new Response(json_encode($resopnse));
    }
}