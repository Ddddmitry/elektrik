<?php

use Bitrix\Main\EventManager;

/*
* Обработчик событий после добавления элементов инфоблока
* */
EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnAfterIBlockElementAdd",
    array(
        "Electric\EventHandlers\IblockHandler",
        "OnAfterIBlockElementAddHandler",
    )
);

/*
* Обработчик событий после обновления элементов инфоблока
* */
EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnAfterIBlockElementUpdate",
    array(
        "Electric\EventHandlers\IblockHandler",
        "OnAfterIBlockElementUpdateHandler",
    )
);

/*
* Обработчик событий перед обновлением элементов инфоблока
* */
EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnBeforeIBlockElementUpdate",
    array(
        "Electric\EventHandlers\IblockHandler",
        "OnBeforeIBlockElementUpdateHandler",
    )
);

/*
* Обработчик событий удаления элементов инфоблока
* */
EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnBeforeIBlockElementDelete",
    array(
        "Electric\EventHandlers\IblockHandler",
        "OnBeforeIBlockElementDeleteHandler",
    )
);

