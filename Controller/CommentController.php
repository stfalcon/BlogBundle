<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * CommentController
 */
class CommentController extends Controller
{

    /**
     * Synchronization comments count with disqus
     *
     * @Route("/blog/post/{slug}/disqus-sync", name="blog_post_disqus_sync")
     */
    public function disqusSyncAction(Post $post)
    {
        $post->setCommentsCount($post->getCommentsCount() + 1);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($post);
        $em->flush($post);
        
        return new Response(json_encode('ok'));
    }
}