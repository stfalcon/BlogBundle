<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Tag;

/**
 * TagController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagController extends Controller
{

    /**
     * View tag
     *
     * @param Tag $tag
     * @param int      $page     Page number
     *
     * @return array
     * @Route("/blog/tag/{text}/{title}/{page}", name="blog_tag_view", requirements={"page" = "\d+"}, defaults={"page" = "1", "title" = "page"})
     * @Template()
     */
    public function viewAction(Tag $tag, $page)
    {
        $pageRange = $this->container->getParameter('page_range');
        $posts = $this->get('knp_paginator')->paginate($tag->getPosts(), $page, $pageRange);

        if ($this->has('menu.breadcrumbs')) {
            $breadcrumbs = $this->get('menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', $this->get('router')->generate('blog'));
            $breadcrumbs->addChild($tag->getText())->setIsCurrent(true);
        }

        return array(
            'tag' => $tag,
            'posts' => $posts,
        );
    }

}