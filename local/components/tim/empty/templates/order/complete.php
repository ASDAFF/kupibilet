<?
/** @var array $order */
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
	<input type="hidden" name="id" value="<?= $order['ID'] ?>" />
	</form><?
}
