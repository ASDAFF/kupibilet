<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Заказ");

$APPLICATION->IncludeComponent('tim:empty', 'order', array(
	'PAGE' => 'complete',
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
