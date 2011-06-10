<?php

namespace Stfalcon\Bundle\BlogBundle\Extension;

class ReadMoreTwigExtension extends \Twig_Extension
{

    public static $separator = '<!--more-->';

    public function getFilters()
    {
        return array(
            'cutMore' => new \Twig_Filter_Method($this, 'cutMore'),
            'moreToSpan' => new \Twig_Filter_Method($this, 'moreToSpan'),
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
     * @return string
     */
    public function cutMore($value)
    {
        $posMore = ((int) strpos($value, self::$separator));
        if ($posMore) {
            return substr($value, 0, $posMore);
        }
        return $value;
    }

    /**
     * Check or text has "more" tag
     *
     * @param string $value
     * @return boolean
     */
    public function hasMore($value)
    {
        return (bool) substr($value, 0, (int) strpos($value, self::$separator));
    }

    public function moreToSpan($value)
    {
        return str_replace(self::$separator, '<span id="more"></span>', $value);
    }

}