<?php

namespace Stfalcon\Bundle\BlogBundle\Extension;

class ReadMoreTwigExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            'cutMore' => new \Twig_Filter_Method($this, 'cutMore'),
        );
    }

    public function getFunctions()
    {
        return array(
            'hasMore' => new \Twig_Function_Method($this, 'hasMore'),
        );
    }

    public function getName()
    {
        return 'read_more';
    }

    /**
     * Cut text before "more" tag
     *
     * @param string $value
     * @param string $separator
     * @return string
     */
    public function cutMore($value, $separator = '<!--more-->')
    {
        $posMore = ((int) strpos($value, $separator));
        if ($posMore) {
            return substr($value, 0, $posMore);
        }
        return $value;
    }

    /**
     * Check or text has "more" tag
     *
     * @param string $value
     * @param string $separator
     * @return boolean
     */
    public function hasMore($value, $separator = '<!--more-->')
    {
        return (bool) substr($value, 0, (int) strpos($value, $separator));
    }

}