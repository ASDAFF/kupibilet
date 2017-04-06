<?
/** @var array $order */

?>
<div class="engBox">
	<div class="elFormAuth">
		<p>Оплата прошла успешно.<?
			if (!$order['PRICE_DELIVERY'])
			{
				?> Теперь Вы можете <a href="/personal/order/print/?id=<?= $order['ID'] ?>">распечатать билет</a>
				и посетить выбранное мероприятие.<?
			}
			?>
        </p>
		<p>Желаем Вам приятно провести время!</p>
	</div>
</div><?