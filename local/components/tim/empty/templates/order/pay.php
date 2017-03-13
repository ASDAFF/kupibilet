<?
/** @var array $order */

$host = $_SERVER['HTTP_HOST'];

if ($order['STATUS_ID'] == 'F')
{
	?>
	<p>Заказ уже оплачен</p>
	<p><a href="/personal/order/print/?id=<?= $order['ID'] ?>">Распечатать</a></p><?
}
else
{

	$client = new Voronkovich\SberbankAcquiring\Client(array(
		'userName' => 'kupibilet-api',
		'password' => 'kupibilet',
		'apiUri' => Voronkovich\SberbankAcquiring\Client::API_URI_TEST,
	));

	$orderId = $order['ID'];
	$orderAmount = $order['PRICE'] * 100;
	$returnUrl = 'http://' . $host . '/personal/order/payment/success/' . $order['ID'] . '/';
	$params = array();
	$params['failUrl'] = 'http://' . $host . '/personal/order/payment/error.php';

	$result = $client->registerOrder($orderId, $orderAmount, $returnUrl, $params);

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