<?php
namespace Electric;

/**
 * Class Notification
 *
 * Класс, содержащий методы для работы почтовыми уведомлениями
 *
 * @package Electric
 */
class Notification
{
    public static function sendNotification($eventCode, $arMailData, $arFiles = false) {
        \CEvent::Send($eventCode, "s1", $arMailData, "Y", false, $arFiles, "ru");
    }
}
