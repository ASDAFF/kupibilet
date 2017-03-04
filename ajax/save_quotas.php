<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$ID = $_REQUEST['ID'];
$quotas = file_get_contents("php://input");

\Local\Main\Run::updateQuotas($ID, $quotas);

echo '{}';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");