<?
/** @var array $order */

use Voronkovich\SberbankAcquiring\Client;

$host = $_SERVER['HTTP_HOST'];

if ($order['STATUS_ID'] == 'F')
{
	?>
	<p>Заказ уже оплачен</p>
	<p><a href="/personal/order/print/?id=<?= $order['ID'] ?>">Распечатать</a></p><?
}
elseif ($order['STATUS_ID'] == 'O')
{
	?>
	<p>Заказ просрочен</p><?
}
elseif ($order['XML_ID'])
{
	header('Location: https://securepayments.sberbank.ru/payment/merchants/kupibilet/payment_ru.html?mdOrder=' .
		$order['XML_ID']);
}
else
{

	$client = new Client(array(
		'userName' => 'kupibilet-api',
		'password' => 'kupibilet',
		//'apiUri' => Client::API_URI_TEST,
	));

	$orderId = $order['ID'];
	$orderAmount = $order['PRICE'] * 100;
	$returnUrl = 'http://' . $host . '/personal/order/payment/success/' . $order['ID'] . '/';
	$params = array();
	$params['failUrl'] = 'http://' . $host . '/personal/order/payment/error.php';

	$result = array();
	try
	{
		$result = $client->registerOrder($orderId, $orderAmount, $returnUrl, $params);
	}
	catch (\Exception $e)
	{
		LocalRedirect('/personal/order/payment/error.php');
	}

	$paymentOrderId = $result['orderId'];
	$paymentFormUrl = $result['formUrl'];

	if ($paymentOrderId)
	{
		\Local\Sale\Cart::prolongReserve($orderItems['ITEMS']);
		\Local\Sale\Cart::setSbOrderId($order['ID'], $paymentOrderId);
		header('Location: ' . $paymentFormUrl);
	}
	else
		LocalRedirect('/personal/order/payment/error.php');
}