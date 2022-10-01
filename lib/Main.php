<?php

namespace Webokami\ComContacts;

use Bitrix\Main\Config\Option;

class Main
{

    public function getContacts()
    {
        $module_id = pathinfo(dirname(__DIR__))["basename"];

        $arr = [];

        $arr[] = Option::get($module_id, 'phone', '');
        $arr[] = Option::get($module_id, 'post', '');
        $arr[] = Option::get($module_id, 'email', '');
        $arr[] = Option::get($module_id, 'telegram', '');
        $arr[] = Option::get($module_id, 'watsapp', '');

        return $arr;
    }

    public function ChangeMyContent(&$content)
    {
        if (!defined("ADMIN_SECTION") && ADMIN_SECTION !== true) {

            $replace = [
                "#Phone#",
                "#Post#",
                "#Email#",
                "#Telegram#",
                "#WatsApp#"
            ];

            $content = str_replace($replace, self::getContacts(), $content);
        }

        return false;
    }
}