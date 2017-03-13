<?
/** @var array $order */
/** @var array $orderItems */

?>
<p>Заказ <?= $order['ID'] ?> создан</p><?

if ($order['STATUS_ID'] == 'F')
{
	?>
	<p>Заказ уже оплачен</p>
	<p><a href="/personal/order/print/?id=<?= $order['ID'] ?>">Распечатать</a></p><?
}
else
{
	\Local\Sale\Cart::prolongReserve($orderItems['ITEMS']);

	?>
	<form action="/personal/order/payment/" method="post">
		<input type="submit" value="Оплатить заказ" />
		<input type="hidden" name="id" value="<?= $order['ID'] ?>" />
	</form><?
}
