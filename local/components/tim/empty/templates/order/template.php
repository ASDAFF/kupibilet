<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs('/js/cart.js');

$id = $_REQUEST['id'];
$order = \Local\Sale\Cart::getOrderById($id);
if (!$order)
	return;

/** @var array $arParams */
if ($arParams['PAGE'] == 'complete')
{

}

if ($arParams['PAGE'] == 'complete')
	include ('complete.php');
elseif ($arParams['PAGE'] == 'pay')
	include ('pay.php');
elseif ($arParams['PAGE'] == 'success')
	include ('success.php');

?>
	<p>Спасибо, заказ принят.</p><?

if ($order['PAYED'] == 'Y')
{
	?>
	<p>Заказ уже оплачен</p><?
}
else
{
	?>
	<form action="/personal/order/payment/" method="post">
	<input type="submit" value="Оплатить заказ" />
	<input type="hidden" name="id" value="<?= $id ?>" />
	</form><?
}


$id = $_REQUEST['id'];
$order = \Local\Sale\Cart::getOrderById($id);
if (!$order)
	return;

$host = $_SERVER['HTTP_HOST'];

use Voronkovich\SberbankAcquiring\Client;

$client = new Client(array(
	'userName' => 'kupibilet-api',
	'password' => 'kupibilet',
	'apiUri' => Client::API_URI_TEST,
));

$orderId     = $id;
$orderAmount = $order['PRICE'] * 100;
$returnUrl   = 'http://' . $host . '/personal/order/payment/success.php';
$params = array();
$params['failUrl']  = 'http://' . $host . '/personal/order/payment/error.php';

$result = $client->registerOrder($orderId, $orderAmount, $returnUrl, $params);

$paymentOrderId = $result['orderId'];
$paymentFormUrl = $result['formUrl'];

debugmessage($result);
debugmessage($paymentOrderId);
debugmessage($paymentFormUrl);

//header('Location: ' . $paymentFormUrl);