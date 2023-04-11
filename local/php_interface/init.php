<?php

use Bitrix\Main\Localization\Loc;

\Bitrix\Main\Loader::includeModule('up.litlab');
Loc::setCurrentLang(LANGUAGE_ID);
Loc::loadMessages(__FILE__);