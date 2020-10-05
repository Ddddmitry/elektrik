<?php

use Bitrix\Main\EventManager;

/*
* Обработчик событий после добавления элементов highload блока
* */
EventManager::getInstance()->addEventHandler(
    "",
    "ContractorToServiceOnAfterAdd",
    array(
        "Electric\EventHandlers\HighloadHandler",
        "ContractorToServiceOnAfterAddHandler",
    )
);

/*
* Обработчик событий после удаления элементов highload блока
* */
EventManager::getInstance()->addEventHandler(
    "",
    "ContractorToServiceOnBeforeDelete",
    array(
        "Electric\EventHandlers\HighloadHandler",
        "ContractorToServiceOnBeforeDeleteHandler",
    )
);


