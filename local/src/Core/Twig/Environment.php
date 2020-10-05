<?php
namespace Core\Twig;

use Core\Helpers\EnvironmentHelper;
use Core\Traits\SingletonTrait;

class Environment
{
    use SingletonTrait;
    
    public static function getInstance()
    {
        if (null !== self::$instance) {
            return self::$instance;
        }
        
        global $USER;
        $debug = $USER->IsAdmin() || (defined('TWIG_DEBUG') && TWIG_DEBUG === true);

        if (EnvironmentHelper::getInstance()->isDev()) {
            $debug = true;
        }

        if (!\CSite::InDir('/html/')) {
            $loader = new \Twig_Loader_Filesystem([
                $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/layouts/',
                $_SERVER['DOCUMENT_ROOT'] . '/local/components/',
                $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/',
            ]);
        } else {
            $loader = new \Twig_Loader_Filesystem([
                $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/layouts/html/',
            ]);
        }

        $twigEnv = new \Twig_Environment($loader, array(
            'autoescape' => true,
            'debug'      => $debug,
        ));

        $twigEnv->addExtension(new CoreTwigExtension());
        $twigEnv->addExtension(new BitrixTwigExtension());
        if ($debug) {
            $twigEnv->addExtension(new \Twig_Extension_Debug());
        }
        self::$instance = $twigEnv;
        return self::$instance;
    }

}
