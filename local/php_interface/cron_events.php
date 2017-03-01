<?
$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__).'/../..');
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS',true);
define('BX_NO_ACCELERATOR_RESET', true);
define('CHK_EVENT', true);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

@set_time_limit(0);
@ignore_user_abort(true);

$agent = new \CAgent();
$event = new \CEvent();

// Периодические
$agent->CheckAgents();

// Не периодические
define('BX_CRONTAB', true);
$agent->CheckAgents();

// Почта
define("BX_CRONTAB_SUPPORT", true);
$event->CheckEvents();