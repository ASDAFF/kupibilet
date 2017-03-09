<?
/** @var array $order */

use Voronkovich\SberbankAcquiring\Client;
use Voronkovich\SberbankAcquiring\OrderStatus;

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
		\Local\Sale\Cart::setOrderPayed($order['ID'], $sbOrderId);
		$ok = true;
	}
}

if (!$ok)
	return;

?>
<p>Заказ успешно оплачен</p>
<p><a href="/personal/order/print/?id=<?= $order['ID'] ?>">Распечатать</a></p><?