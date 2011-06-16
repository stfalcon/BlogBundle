<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class EntitiesToStringTransformer implements DataTransformerInterface
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Transforms entities into choice keys
     *
     * @param Collection|object $collection A collection of entities, a single entity or
     *                                      NULL
     * @return mixed An array of choice keys, a single key or NULL
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return null;
        }

        if (!($collection instanceof Collection)) {
            throw new UnexpectedTypeException($collection, 'Doctrine\Common\Collections\Collection');
        }

        $array = array();
        foreach ($collection as $entity) {
            array_push($array, $entity->getText());
        }

        return implode(', ', $array);
    }

    public function reverseTransform($data)
    {
        $collection = new ArrayCollection();

        if ('' === $data || null === $data) {
            return $collection;
        }
        
        if (!is_string($data)) {
            throw new UnexpectedTypeException($data, 'string');
        }

        $tags = explode(',', $data);
        // strip whitespace
        foreach($tags as &$text) {
            $text = trim($text);
        }
        unset($text);

        // removes duplicates
        $tags = array_unique($tags);

        // @todo если пусто?
        foreach ($tags as $text) {
            $tag = $this->em->getRepository("StfalconBlogBundle:Tag")
                    ->findOneBy(array('text' => $text));
            if (!$tag) {
                $tag = new \Stfalcon\Bundle\BlogBundle\Entity\Tag($text);
                $this->em->persist($tag);
            }
            $collection->add($tag);
        }

        return $collection;
    }

}