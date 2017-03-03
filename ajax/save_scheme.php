<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$ID = $_REQUEST['ID'];
$scheme = file_get_contents("php://input");

\Local\Main\Hall::updateScheme($ID, $scheme);

echo '{}';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");