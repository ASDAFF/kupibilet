<?
/** @global CMain $APPLICATION */

$isAjax = isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'ajax';
if ($isAjax)
{
	define('PUBLIC_AJAX_MODE', true);
	define('STOP_STATISTICS', true);
	define('NO_AGENT_CHECK', true);
	require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
}
else
	require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

$APPLICATION->IncludeComponent('tim:events', '', array(
	'AJAX' => $isAjax,
));

if (!$isAjax)
	require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';

