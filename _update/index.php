<?

if (!$_SERVER["DOCUMENT_ROOT"]) {
	error_reporting(0);
	setlocale(LC_ALL, 'ru.UTF-8');
	$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/..");
	$console = true;
}
else {
	$console = false;
}


define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$user = new CUser();
if (!$console && !$user->IsAdmin())
	return;

if (!$console)
	echo '<pre>';

\Bitrix\Main\Loader::includeModule('sale');

include ('admin_template.php');
include ('add_quotas.php');
include ('field_longtext.php');
include ('add_order_status.php');
//include ('change_mail_template.php');
include ('cart_cache_hl_block.php');
include ('add_property_hall_to_run.php');
include ('add_property_zone.php');

if (!$console)
	echo '</pre>';


