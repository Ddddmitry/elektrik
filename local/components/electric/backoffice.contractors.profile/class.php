<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Cassandra\Varint;
use \Electric\Component\BaseComponent;
use Electric\Contractor;
use Electric\Location;
use Electric\Order;
use Electric\Service;
use Electric\User;
use Electric\Vendor;
use \Electric\Helpers\DataHelper;

class ContractorsProfile extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arContractor;

    private $folder;

    protected $curUserId;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных Исполнителя
     *
     * @return array|bool
     */
    protected function getContractor()
    {
        global $USER;
        $this->curUserId = $USER->GetID();
        $obContractor = new Contractor();

        $arContractor = $obContractor->getContractorData();
        $arContractor["REVIEWS_COUNT"] = $obContractor->getReviewsCount($this->curUserId);



        $reviewsCount = $obContractor->getReviewsCount();
        $reviewsCountByMonth = $obContractor->getReviewsCount(null, (new \DateTime())->modify('-1 month'));

        $rating = $obContractor->getRating();
        $ratingChanges = $obContractor->getRatingChanges();

        $watchWorksheet = $this->arHLDataClasses["VIEWS_DETAIL"]::getCount(["UF_USER" => $this->curUserId]);
        $watchContacts = $this->arHLDataClasses["VIEWS_CONTACTS"]::getCount(["UF_USER" => $this->curUserId]);
        $date = (\Bitrix\Main\Type\DateTime::createFromPhp((new \DateTime())->modify('-1 month')))->toString();
        $dateWeek = (\Bitrix\Main\Type\DateTime::createFromPhp((new \DateTime())->modify('-1 week')))->toString();
        $watchWorksheetByMonth = $this->arHLDataClasses["VIEWS_DETAIL"]::getCount(["UF_USER" => $this->curUserId, ">UF_DATE" => $date]);
        $watchWorksheetByWeek = $this->arHLDataClasses["VIEWS_DETAIL"]::getCount(["UF_USER" => $this->curUserId, ">UF_DATE" => $dateWeek]);
        $watchContactsByMonth = $this->arHLDataClasses["VIEWS_CONTACTS"]::getCount(["UF_USER" => $this->curUserId, ">UF_DATE" => $date]);
        $arContractor["STATISTICS"] = [
            "all" => [
                "raitingNumber" => $rating,
                "reviewsNumber" => $reviewsCount,
                "watchWorksheet" => $watchWorksheet,
                "watchContacts" => $watchContacts,
            ],
            "month" => [
                "raitingNumber" => $ratingChanges["LAST_MONTH"],
                "reviewsNumber" => $reviewsCountByMonth,
                "watchWorksheet" => $watchWorksheetByMonth,
                "watchContacts" => $watchContactsByMonth,
            ],
            "week" => [
                "watchWorksheet" => $watchWorksheetByWeek,
            ]
        ];
        $arContractor["LANGUAGES"] = $obContractor->getLanguages($arContractor["ID"]);
        $arContractor["EXPERIENCE"] = $obContractor->getJobs($arContractor["ID"]);
        $arContractor["WORKS"] = $obContractor->getWorks($this->curUserId);
        $arContractor["SERVICES"] = $obContractor->getGroupedServices($arContractor["ID"]);

        if ($arContractor["PROPERTIES"]["LOCATIONS"]) {
            foreach ($arContractor["PROPERTIES"]["LOCATIONS"] as $arLocation) {

                // Получение данных локации из кэш-таблицы. Если такой записи нет - запросом к API Dadata
                if ($arCachedLocation = Location::getCachedLocationByID($arLocation["VALUE"])) {
                    $arContractor["PLACES"][] = [
                        "ID" => $arCachedLocation["UF_FIAS_ID"],
                        "NAME" => $arCachedLocation["UF_NAME"],
                        "LEVEL" => $arCachedLocation["UF_LEVEL"],
                    ];
                } else {
                    $arLocationData = Location::getLocationDataByID($arLocation["VALUE"]);
                    $arContractor["PLACES"][] = [
                        "ID" => $arLocation["VALUE"],
                        "NAME" => $arLocationData["DISPLAY_NAME"],
                        "LEVEL" => $arLocationData["data"]["fias_level"],
                    ];
                }

            }
        }
        if ($arContractor["PROPERTIES"]["ROOM"]) {
            $arSelectedRooms = array_column($arContractor["PROPERTIES"]["ROOM"], "VALUE");
            $obPropertyEnum = \CIBlockPropertyEnum::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => IBLOCK_CONTRACTORS, "CODE" => "ROOM"]);
            while ($arPropertyEnum = $obPropertyEnum->Fetch()) {
                $arContractor["ROOMS"][$arPropertyEnum["ID"]] = [
                    "NAME" => $arPropertyEnum["VALUE"],
                    "CHECKED" => in_array($arPropertyEnum["ID"], $arSelectedRooms),
                ];
            }
        }

        $arEducations = $obContractor->getEductaions($arContractor["ID"]);
        foreach ($arEducations as $arEducation) {
            $arContractor["EDUCATIONS"][] = [
                "PLACE" => $arEducation["NAME"],
                "TYPE" => $arEducation["STATUS"],
            ];
        }
        $arCourses = $obContractor->getCourses($arContractor["ID"]);
        foreach ($arCourses as $arCourse) {
            $arContractor["COURSES"][] = [
                "NAME" => $arCourse["NAME"],
            ];
        }


        // Проверка сертификаций и наличия заявок на сертификации
        $rsUser = \CUser::GetByID($this->curUserId);
        $arUser = $rsUser->Fetch();
        $obUser = new User();
        $arContractor["IS_CERTIFIED"] = $arUser["UF_SERTIFIED"] ? true : false;
        $arContractor["HAS_CERTIFIED_REQUEST"] = $obUser->hasCertificationRequest();
        $arContractor["IS_RAEK_MEMBER"] = $arUser["UF_RAEK"] ? true : false;
        $arContractor["HAS_RAEK_REQUEST"] = $obUser->hasRaekRequest() || $obUser->hasRaekVerification();


        if ($arContractor) {

            if(!$arContractor["PREVIEW_PICTURE"])
                $arContractor["PREVIEW_PICTURE"]["SRC"] = "/local/templates/elektrik/images/contractor-empty.png";

            $arContractor["ARTICLES"] = $obContractor->getArticles();

            $obOrder = new Order();
            $arContractor["ORDERS"] = $obOrder->getOrders();

            $arContractor["ORDERS_COUNT"] = count($arContractor["ORDERS"]);
            $arContractor["CONTRACTOR_ORDER"] = $obOrder->getContractorOrder();

            $arContractor["STATUS"] = $obOrder->getArStatus();
            $arContractor["NEW_ORDERS_ID"] = $obOrder->getNewOrders();

            $arContractor["ORDERS"] = $this->sortingOrders($arContractor["ORDERS"],$arContractor["CONTRACTOR_ORDER"],$arContractor["NEW_ORDERS_ID"]);

            /**
             * Варинаты языков
            */
            $rsData = $this->arHLDataClasses["LANGUAGES_LIST"]::getList(["select" => ['*'],"filter" => [],"order" => [],]);
            while ($arItem = $rsData->Fetch()) {
                $arContractor["LIST_LANGUAGES_NAMES"][] = [
                    "ID" => $arItem["ID"],
                    "NAME" => $arItem["UF_NAME"]
                ];
            }
            /**
             * Варинаты уровеней языка
             */
            $rsData = $this->arHLDataClasses["LANGUAGES_LEVELS"]::getList(["select" => ['*'],"filter" => [],"order" => [],]);
            while ($arItem = $rsData->Fetch()) {
                $arContractor["LIST_LANGUAGES_LEVELS"][] = [
                    "ID" => $arItem["ID"],
                    "NAME" => $arItem["UF_NAME"]
                ];
            }
            /**
             * Варинаты навыков
             */
            $rsData = $this->arHLDataClasses["SKILLS"]::getList(["select" => ['*'],"filter" => [],"order" => [],]);
            while ($arItem = $rsData->Fetch()) {
                $arContractor["LIST_SKILLS"][] = [
                    "ID" => $arItem["ID"],
                    "NAME" => $arItem["UF_NAME"]
                ];
            }

            for ($i=0;$i<12;$i++)
                $arContractor["LIST_MONTH"][] = DataHelper::getMonth($i);

            $arContractor["LIST_EDUCATION_TYPE"] = LIST_EDUCATION_TYPE;

            /**
             * Варианты услуг
             */
            $obService = new Service();
            $arGroupedServices = $obService->getAllServices();

            foreach ($arGroupedServices as $key => $arSection) {
                $arContractor["LIST_SERVICES"][$key] = [
                    "name" => $arSection["NAME"],
                ];
                foreach ($arSection["SERVICES"] as $arService) {
                    $arContractor["LIST_SERVICES"][$key]["items"][$arService["ID"]] = [
                        "name" => $arService["NAME"],
                        "added" => false,
                        "price" => "",
                    ];
                }
            }

            $this->arContractor = $arContractor;

            return true;
        }

        return false;
    }

    /**
     * Сортируем все заказы по статусам
     * @param $arOrders - заказы
     * @param $arNewOrders - ID-шники новых заказов
     * @return array
     */
    protected function sortingOrders($arOrders,$arContractorOrder,$arNewOrdersIds){

        $arResult = [];
        $arOrdCont = [];
        if(!$arOrders)
            return false;


        foreach ($arContractorOrder as $arValues){
            $arOrdCont[$arValues['ORDER']] = $arValues['STATUS'];
        }

//var_dump($arOrdCont);
        foreach ($arOrders as $arOrder) {
            //var_dump($arOrder['PROPERTIES']['STATUS']['VALUE_XML_ID']);
            foreach ($arOrdCont as $orderId => $orderStatus) {
                if($arOrder['ID'] == $orderId){
                    if(!in_array($arOrder['ID'],$arNewOrdersIds)
                        && $arOrder['PROPERTIES']['STATUS']['VALUE_XML_ID'] == "new" )
                        continue;

                    $arResult[$arOrder['PROPERTIES']['STATUS']['VALUE_XML_ID']][] = $arOrder;
                }
            }
        }

        return $arResult;
    }

    /**
     * Фильтруем из всех заказов только Новые
     * @param $arOrders - заказы
     * @param $arNewOrders - ID-шники новых заказов
     * @return array
     */
    protected function filterNewOrders($arOrders, $arNewOrders){

        $arResult = [];

        foreach ($arOrders as $arOrder) {
            if(in_array($arOrder['ID'],$arNewOrders)){
                $arResult[] = $arOrder;
            }
        }

        return $arResult;
    }

    private function getHLDataClasses() {
        \CModule::IncludeModule('highloadblock');

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_VIEWS_DETAIL)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["VIEWS_DETAIL"] = $obEntity->getDataClass();

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_VIEWS_CONTACTS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["VIEWS_CONTACTS"] = $obEntity->getDataClass();

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_SKILLS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["SKILLS"] = $obEntity->getDataClass();

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_LANGUAGES_LIST)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["LANGUAGES_LIST"] = $obEntity->getDataClass();

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_LANGUAGES_LEVELS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["LANGUAGES_LEVELS"] = $obEntity->getDataClass();

    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }
        $this->getHLDataClasses();

        if ($this->StartResultCache()) {
            if ($this->getContractor() !== false) {
                $this->arResult['PROFILE'] = $this->arContractor;
                $this->arResult['SEF_FOLDER'] = $this->folder;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }
    }
}
