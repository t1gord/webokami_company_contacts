<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

if(class_exists('webokami_companycontacts')) return;

class webokami_companycontacts extends CModule
{
    public function __construct()
    {
        if (file_exists(__DIR__ . '/version.php')) {

            $arModuleVersion = array();
            include_once(__DIR__ . '/version.php');

            $this->MODULE_ID = str_replace('_', '.', get_class($this));
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME = Loc::getMessage('WEBOK_COMPCONTS_MODULE_NAME');
            $this->MODULE_DESCRIPTION = Loc::getMessage('WEBOK_COMPCONTS_MODULE_DESCRIPTION');
            $this->PARTNER_NAME = Loc::getMessage('WEBOK_COMPCONTS_MODULE_PARTNER_NAME');
            $this->PARTNER_URI = Loc::getMessage('WEBOK_COMPCONTS_MODULE_PARTNER_URI');
        }

        return false;
    }

    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }

    public function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            'Webokami\ComContacts\Main',
            'ChangeMyContent'
        );
    }

    public function DoInstall()
    {
        global $APPLICATION;

        try {
            if ($this->isVersionD7()) {
                ModuleManager::registerModule($this->MODULE_ID);

                $this->InstallEvents();
            } else {
                throw new SystemException(Loc::getMessage('WEBOK_COMPCONTS_MODULE_INSTALL_ERROR_VERSION'));
            }
        } catch (SystemException $exception) {
            $exception->getMessage();
        }

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('WEBOK_COMPCONTS_MODULE_INSTALL_TITLE') . " \"" . Loc::getMessage('WEBOK_COMPCONTS_MODULE_NAME') . "\"",
            __DIR__ . '/step.php'
        );

        return false;
    }

    public function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            'Webokami\ComContacts\Main',
            'ChangeMyContent'
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('WEBOK_COMPCONTS_MODULE_UNINSTALL_TITLE') . " \"" . Loc::getMessage('WEBOK_COMPCONTS_MODULE_NAME') . "\"",
            __DIR__ . '/unstep.php'
        );

        return false;
    }
}