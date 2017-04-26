<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sale/include.php");

$result = ['SUCCESS' => 1];

if ($_REQUEST['action'] == 'remove')
{
	$orderId = $_REQUEST['order_id'];
	$cart = new \CSaleBasket();
	$carts = $cart->GetList([], ['ORDER_ID' => $orderId]);
	$deleteCarts = true;
	$deleteReserved = true;
	$deleteOrder = true;

	while($item = $carts->Fetch())
	{
		$cart->Delete($item['ID']);
		\Local\Sale\Reserve::delete($item['ID']);
	}

	$order = new CSaleOrder();
	$order->Delete($orderId);

}

header('Content-Type: application/json');
echo json_encode($result);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");