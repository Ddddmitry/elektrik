<?
namespace Electric;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;

class MainContractors extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['CACHE_TIME'] = ($arParams['CACHE_TIME']) ? $arParams['CACHE_TIME'] : '36000';

        return $arParams;
    }

    public function executeComponent()
    {
        $this->arResult = null;
        $this->arResult = $this->getComponentPage($this->arParams);
        $this->arResult['PATH_URL_TEMPLATES'] = $this->getPathFromUrlTemplate($this->arParams['SEF_URL_TEMPLATES'], $this->arParams['SEF_FOLDER']);
        $this->arResult['MAKE_PAGES_URL'] = $this->getMakePagesUrl($this->arResult['PATH_URL_TEMPLATES'], $this->arResult['VARIABLES']);

        if (!$this->arResult['COMPONENT_PAGE']) {
            $this->includeComponentTemplate("list");
            //$this->set404();
        } else {
            $this->includeComponentTemplate($this->arResult['COMPONENT_PAGE']);
        }
    }

    /**
     * Метод для работы ЧПУ
     *
     * @param $arParams mixed
     * @return mixed
     */
    private function getComponentPage($arParams)
    {
        $arVariables = array();

        $arComponentVariables = array(
            'SECTION_ID',
            'SECTION_CODE',
            'ELEMENT_ID',
            'ELEMENT_CODE',
        );

        $arUrlTemplates = $arParams['SEF_URL_TEMPLATES'];
        $arVariableAliases = (is_array($arParams['VARIABLE_ALIASES'])) ? $arParams['VARIABLE_ALIASES'] : array();

        $engine = new \CComponentEngine($this);

        $engine->addGreedyPart('#SECTION_CODE_PATH#');
        $engine->setResolveCallback(array('CIBlockFindTools', 'resolveComponentEngine'));

        $componentPage = $engine->guessComponentPath(
            $arParams['SEF_FOLDER'],
            $arUrlTemplates,
            $arVariables
        );

        \CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

        return array(
            'FOLDER' => $arParams['SEF_FOLDER'],
            'URL_TEMPLATES' => $arUrlTemplates,
            'VARIABLES' => $arVariables,
            'ALIASES' => $arVariableAliases,
            'COMPONENT_PAGE' => $componentPage,
        );
    }

    /**
     * Выстроить пути до страниц (шаблоны)
     *
     * @param $arUrlTemplates mixed
     * @param $sefFolder string
     * @return mixed
     */
    protected function getPathFromUrlTemplate($arUrlTemplates, $sefFolder)
    {
        if (empty($arUrlTemplates) || empty($sefFolder)) {
            return false;
        }

        foreach ($arUrlTemplates as &$template) {
            $template = $sefFolder . str_replace('index.php', '', $template);
        }

        return $arUrlTemplates;
    }

    /**
     * Выстроить пути до страниц (реальные)
     *
     * @param $arUrlTemplates mixed
     * @param $arVariables mixed
     * @return mixed
     */
    protected function getMakePagesUrl($arPathUrlTemplates, $arVariables)
    {
        if (empty($arPathUrlTemplates)) {
            return false;
        }

        foreach ($arPathUrlTemplates as &$template) {
            $template = \CComponentEngine::MakePathFromTemplate($template, $arVariables);
        }

        return $arPathUrlTemplates;
    }
}

?>
