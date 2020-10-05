<?php
define("SEO_ID","21");
use Bitrix\Highloadblock as HL;
function checkSEO(){
    global $APPLICATION;
    \Bitrix\Main\Loader::includeModule('highloadblock');
    $hIB_ID = SEO_ID;
    $hlblock = null;
    //$curpage = $APPLICATION->GetCurPage(); // Без GET параметров
    $curpage = $APPLICATION->GetCurUri();//С GET параметрами
    $hlblock = HL\HighloadBlockTable::getById($hIB_ID)->fetch();
    if (!empty($hlblock))
    {
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $entity_table_name = $hlblock['TABLE_NAME'];
        $arFilter = array("UF_URL"=>$curpage); //задаете фильтр по вашим полям
        $sTableID = 'tbl_'.$entity_table_name;
        $rsData = $entity_data_class::getList(array(
            "select" => array('*'),
            "filter" => $arFilter,
            "order" => array()
        ));
        $rsData = new CDBResult($rsData, $sTableID);
        if($arFields = $rsData->Fetch())
        {
            if(!empty($arFields["UF_TITLE"]))
                $GLOBALS["SEO"]["TITLE"] = $arFields["UF_TITLE"];

            if(!empty($arFields["UF_KEYWORDS"]))
                $GLOBALS["SEO"]["KEYWORDS"] = $arFields["UF_KEYWORDS"];

            if(!empty($arFields["UF_DESCRIPTION"]))
                $GLOBALS["SEO"]["DESCRIPTION"] = $arFields["UF_DESCRIPTION"];
        }
    }
}
?>
