<?php

namespace Core\Helpers;

use \Bitrix\Main\Context;
use Core\Traits\SingletonTrait;

class EnvironmentHelper
{
    use SingletonTrait;

    const
        DEVELOPMENT = 'development',
        PREPRODUCTION = 'preproduction',
        PRODUCTION = 'production';

    private $guessedEnvironment = false;


    protected function __construct()
    {
        $this->guessEnvironment();
    }

    private function guessEnvironment()
    {
        if (!$this->guessedEnvironment) {

            if ((defined('DEV')) && (DEV == true)) {
                $this->guessedEnvironment = self::DEVELOPMENT;
                return $this->guessedEnvironment;
            }

            if ((defined('PROD')) && (PROD == true)) {
                $this->guessedEnvironment = self::PRODUCTION;
                return $this->guessedEnvironment;
            }

            if ((defined('PREPROD')) && (PREPROD == true)) {
                $this->guessedEnvironment = self::PREPRODUCTION;
                return $this->guessedEnvironment;
            }

            if (strpos($_SERVER['SERVER_NAME'], 'preprod.articul.ru')) {
                $this->guessedEnvironment = self::PREPRODUCTION;
            } elseif ((strpos($_SERVER['SERVER_NAME'], 'articul.ru') !== false)) {
                $this->guessedEnvironment = self::DEVELOPMENT;
            } else {
                $this->guessedEnvironment = self::PRODUCTION;
            }
        }

        return $this->guessedEnvironment;
    }

    public function getEnvironment() {
        return $this->guessedEnvironment;
    }

    public function setEnvironment($env) {
        $this->guessedEnvironment = $env;
        return $this;
    }

    public function isDev() {
        if ($this->getEnvironment() == self::DEVELOPMENT) {
            return true;
        }

        return false;
    }

    public function isPreproduction() {
        if ($this->getEnvironment() == self::PREPRODUCTION) {
            return true;
        }

        return false;
    }

    public function isProd() {
        if ($this->getEnvironment() == self::PRODUCTION) {
            return true;
        }

        return false;
    }

    public function parseRequest() {
        if (in_array(Context::getCurrent()->getRequest()->getRequestMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $input = trim(file_get_contents('php://input'));
            if (strlen($input)) {

                $_POST = json_decode($input, true);
                if (!$_POST) {
                    parse_str($input, $_POST);
                }

                $_REQUEST = array_merge($_REQUEST, $_POST);
                Context::getCurrent()->getRequest()->getPostList()->set($_POST);
            }
        }
    }
}
