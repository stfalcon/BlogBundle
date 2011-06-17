<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Tag;
use Stfalcon\Bundle\BlogBundle\Form\TagForm;

/**
 * TagController
 */
class TagController extends Controller
{

    /**
     * View tag
     *
     * @Route("/{_locale}/blog/tag/{slug}", name="blog_tag_view",
     *      defaults={"_locale"="ru"}, requirements={"_locale"="ru|en"})
     * @Template()
     */
    public function viewAction($slug)
    {
        $tag = $this->_findTagBySlug($slug);

        if ($this->has('menu.breadcrumbs')) {
            $breadcrumbs = $this->get('menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', $this->get('router')->generate('blog'));
            $breadcrumbs->addChild($tag->getText())->setIsCurrent(true);
        }
        
        return array(
            'tag' => $tag,
        );
    }

    /**
     * Try find tag by id
     *
     * @param int $slug
     * @return Category
     */
    private function _findTagBySlug($slug)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $tag = $em->getRepository("StfalconBlogBundle:Tag")
                ->findOneBy(array('text' => $slug));
        if (!$tag) {
            throw new NotFoundHttpException('The tag does not exist.');
        }

        return $tag;
    }
}