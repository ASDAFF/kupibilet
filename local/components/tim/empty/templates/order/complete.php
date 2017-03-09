<?
/** @var array $order */

?>
<p>Заказ <?= $order['ID'] ?> создан</p><?

if ($order['STATUS_ID'] == 'F')
{
	?>
	<p>Заказ уже оплачен</p><?
}
else
{
	?>
	<form action="/personal/order/payment/" method="post">
	<input type="submit" value="Оплатить заказ" />
	<input type="hidden" name="id" value="<?= $order['ID'] ?>" />
	</form><?
}
