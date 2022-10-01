<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request['mid'] != '' ? $request['mid'] : $request["id"]);

Loader::includeModule($module_id);

$aTabs = array(
    array(
        "DIV" => "edit",
        "TAB" => Loc::getMessage("WEBOK_COMPCONTS_OPTION_TAB_NAME"),
        "TITLE" => Loc::getMessage("WEBOK_COMPCONTS_OPTION_TAB_NAME")
    )
);

$arAllOptions = array(
    'Основные контакты',
    array('phone', Loc::getMessage('WEBOK_COMPCONTS_OPTION_PHONE'), '', array('text', 24)),
    array('post', Loc::getMessage('WEBOK_COMPCONTS_OPTION_POST'), '', array('text', 50)),
    array('email', Loc::getMessage('WEBOK_COMPCONTS_OPTION_EMAIL'), '', array('text', 24)),
    'Мессенджеры',
    array('telegram', Loc::getMessage('WEBOK_COMPCONTS_OPTION_TELEGRAM'), '', array('text', 20)),
    array('watsapp', Loc::getMessage('WEBOK_COMPCONTS_OPTION_WATSAPP'), '', array('text', 20)),
    array('note' => Loc::getMessage('WEBOK_COMPCONTS_OPTION_MANUAL')),
);

if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($arAllOptions as $arOption) {
        if (!is_array($arOption)) {
            continue;
        }

        if ($arOption['note']) {
            continue;
        }

        if ($request['apply']) {
            $optionValue = $request->getPost($arOption[0]);

            if ($arOption[0] == 'switch_on') {
                if ($optionValue == '') {
                    $optionValue = 'N';
                }
            }

            Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
        } elseif ($request['default']) {
            Option::set($module_id, $arOption[0], $arOption[2]);
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANGUAGE_ID);
}

$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin();
?>

    <form action="<?= $APPLICATION->GetCurPage(); ?>?mid=<?= $module_id ?>&lang=<?= LANGUAGE_ID ?>" method="post">
        <?php
        $tabControl->BeginNextTab();

        if (is_array($arAllOptions)) {
            __AdmSettingsDrawList($module_id, $arAllOptions);
        }

        $tabControl->Buttons();
        ?>

        <input type="submit" name="apply" value="<?= Loc::GetMessage('WEBOK_COMPCONTS_OPTIONS_INPUT_APPLY'); ?>"
               class="adm-btn-save"/>
        <input type="submit" name="default" value="<?= Loc::GetMessage("WEBOK_COMPCONTS_OPTIONS_INPUT_DROP"); ?>"/>

        <?php
        echo(bitrix_sessid_post());
        ?>

    </form>

<?php
$tabControl->End();
?>