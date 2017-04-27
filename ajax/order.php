<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$result = [];

if ($_REQUEST['action'] == 'remove')
{
	$orderId = $_REQUEST['order_id'];
	$res = \Local\Sale\Cart::deleteOrder($orderId);
	$result = ['SUCCESS' => $res];
}

header('Content-Type: application/json');
echo json_encode($result);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");