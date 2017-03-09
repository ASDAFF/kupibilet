<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Заказ");

$APPLICATION->IncludeComponent('tim:empty', 'order', array(
	'PAGE' => 'print',
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
