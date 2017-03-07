<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array();

if ($_REQUEST['action'] == 'add')
{
	$id = \Local\Sale\Cart::add($_REQUEST['id'], $_REQUEST['eid'], $_REQUEST['rid']);
	$result['ID'] = intval($id);
}
elseif ($_REQUEST['action'] == 'remove')
{
	$res = \Local\Sale\Cart::remove($_REQUEST['id'], $_REQUEST['eid'], $_REQUEST['rid']);
	$result['SUCCESS'] = $res ? 1 : 0;
}

$result['CART'] = \Local\Sale\Cart::getSummary();

header('Content-Type: application/json');
echo json_encode($result);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");