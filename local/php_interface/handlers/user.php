<?php

use Bitrix\Main\EventManager;

/*
* Обработчик событий после обновления элементов инфоблока
* */
EventManager::getInstance()->addEventHandler(
    "main",
    "OnAfterUserAdd",
    array(
        "Electric\EventHandlers\UserHandler",
        "OnAfterUserAddHandler",
    )
);


