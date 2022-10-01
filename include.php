<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    "webokami.companycontacts",
    [
        "Webokami\ComContacts\Main" => "lib/Main.php",
    ]);

AddEventHandler("main", "OnEndBufferContent", "ChangeMyContent");