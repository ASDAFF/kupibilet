<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$id = \Local\Sale\Cart::add($_REQUEST['id'], $_REQUEST['eid'], $_REQUEST['rid']);
$result = array(
	'ID' => $id,
);

if ($id)
	$result['CART'] = \Local\Sale\Cart::getSummary();

echo json_encode($result);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");