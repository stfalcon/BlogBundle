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
use Stfalcon\Bundle\BlogBundle\Form\Post as PostForm;

/**
 * ProjectController
 */
class PostController extends Controller
{

    /**
     * Projects list
     *
     * @return array
     * @Route("/admin/blog/posts", name="blogPostIndex")
     * @Template()
     */
    public function indexAction()
    {
        $posts = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPosts();

        return array('posts' => $posts);
    }

    /**
     * Create new post
     *
     * @return array|RedirectResponse
     * @Route("/admin/blog/post/create", name="blogPostCreate")
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
                        'Congratulations, your post is successfully created!');
                return new RedirectResponse($this->generateUrl('blogPostIndex'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * View post
     *
     * @Route("/blog/post/{id}", name="blogPostView")
     * @Template()
     */
    public function viewAction($id)
    {
        $post = $this->_findPostById($id);

//        $breadcrumbs = $this->get('menu.breadcrumbs');
//        $breadcrumbs->addChild($category->getName())->setIsCurrent(true);

        return array(
            'post' => $post,
        );
    }


    /**
     * Try find post by id
     *
     * @param int $id
     * @return Category
     */
    private function _findPostById($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $post = $em->getRepository("StfalconBlogBundle:Post")
                ->findOneBy(array('id' => $id));
        if (!$post) {
            throw new NotFoundHttpException('The post does not exist.');
        }

        return $post;
    }
}