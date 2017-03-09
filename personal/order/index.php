<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Заказ");

$APPLICATION->IncludeComponent('tim:empty', 'order', array(
	'ORDER' => 'Y',
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
