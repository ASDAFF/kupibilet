<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array();

if ($_REQUEST['action'] == 'add')
{
	$id = \Local\Sale\Cart::add($_REQUEST['id'], $_REQUEST['eid'], $_REQUEST['rid']);
	$result['ID'] = intval($id);
}
// Удаление из корзины - со страницы показа
elseif ($_REQUEST['action'] == 'remove')
{
	$res = \Local\Sale\Cart::remove($_REQUEST['id'], $_REQUEST['eid'], $_REQUEST['rid']);
	$result['SUCCESS'] = $res ? 1 : 0;
}
// Удаление из корзины - со страницы корзины
elseif ($_REQUEST['action'] == 'delete')
{
	$res = \Local\Sale\Cart::delete($_REQUEST['id']);
	$result['SUCCESS'] = $res ? 1 : 0;
}

$cartSummary = \Local\Sale\Cart::getSummary();
$expired = 0;
if ($cartSummary['EXPIRED'])
{
	$expired = $cartSummary['EXPIRED'] - time();
	if ($expired < 0)
		$expired = 0;
}
$cartSummary['EXPIRED'] = $expired;
$result['CART'] = $cartSummary;

header('Content-Type: application/json');
echo json_encode($result);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");