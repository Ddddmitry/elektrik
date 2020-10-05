<?php


namespace Core\Twig;


class CoreTwigExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('asset', array($this, 'assetFilter')),
        );
    }

    public function assetFilter($file)
    {
        return $file.'?'.filemtime($_SERVER['DOCUMENT_ROOT'].$file);
    }

    public function getName()
    {
        return 'core_twig_extension';
    }

}


