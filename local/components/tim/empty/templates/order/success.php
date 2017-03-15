<?
/** @var array $order */
/** @var array $orderItems */

use Voronkovich\SberbankAcquiring\Client;
use Voronkovich\SberbankAcquiring\OrderStatus;

if ($order['STATUS_ID'] != 'F')
{
	$client = new Client(array(
		'userName' => 'kupibilet-api',
		'password' => 'kupibilet',
		'apiUri' => Client::API_URI_TEST,
	));

	$sbOrderId = $_REQUEST['orderId'];
	$ok = false;
	if ($sbOrderId)
	{
		$result = $client->getOrderStatus($sbOrderId);
		if (OrderStatus::isDeposited($result['OrderStatus']))
		{
			\Local\Sale\Cart::setOrderPayed($order['ID'], $orderItems['ITEMS']);
			$ok = true;
		}
	}

	if (!$ok)
		return;
}

?>
<div class="engBox">
<div class="elFormAuth">
<p>Оплата прошла успешно. Теперь Вы можете <a href="/personal/order/print/?id=<?= $order['ID'] ?>">распечатать билет</a>
		и посетить выбранное мероприятие.</p>
<p>Желаем Вам приятно провести время!</p>
</div>
</div><?