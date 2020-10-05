<?php

namespace Electric\Helpers;

use Bitrix\Main\Application;

class FileHelper
{

    public static function saveFile($base64Content, $register = false) {
        $arSplit = explode(";base64,", $base64Content);
        $content = $arSplit[1];
        $extension = explode("/", $arSplit[0])[1];
        $fileData = base64_decode($content);
        $uploadPath = $_SERVER["DOCUMENT_ROOT"] . "/upload/trash/";
        $uploadFileName = rand(1000000000, 9999999999) . "." . $extension;
        file_put_contents($uploadPath . $uploadFileName, $fileData);

        if (file_exists($uploadPath . $uploadFileName)) {
            if ($register) {
                $arFile = \CFile::MakeFileArray($uploadPath . $uploadFileName);
                $arFile["MODULE_ID"] = "iblock";
                $fileID = \CFile::SaveFile($arFile, "images");
                unlink($uploadPath . $uploadFileName);

                return $fileID;
            } else {
                $fileTempPath = $uploadPath . $uploadFileName;

                return $fileTempPath;
            }
        } else {

            return false;
        }
    }

    public static function saveFileFromForm($arFile,$path = "trash"){
        $arImage = [];
        $uploadPath = $_SERVER["DOCUMENT_ROOT"] . "/upload/".$path."/";
        $file = file_get_contents($arFile["tmp_name"]);
        file_put_contents($uploadPath . $arFile["name"], $file);
        $arImage = \CFile::MakeFileArray($uploadPath . $arFile["name"]);

        return $arImage;

    }

}
