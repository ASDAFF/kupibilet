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

include ('admin_template.php');

if (!$console)
	echo '</pre>';


