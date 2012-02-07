<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     *
     * @return array
     * @Route("/blog/tag/{text}", name="blog_tag_view")
     * @Template()
     */
    public function viewAction(Tag $tag)
    {
        if ($this->has('menu.breadcrumbs')) {
            $breadcrumbs = $this->get('menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', $this->get('router')->generate('blog'));
            $breadcrumbs->addChild($tag->getText())->setIsCurrent(true);
        }

        return array(
            'tag' => $tag,
        );
    }

}