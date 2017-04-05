<?
/** @var array $order */

?>
<div class="engBox">
	< class="elFormAuth">
		<p>Оплата прошла успешно.
        <? if((int)$order['PRICE_DELIVERY'] == 0): ?>
            Теперь Вы можете <a href="/personal/order/print/?id=<?= $order['ID'] ?>">распечатать билет</a>
            и посетить выбранное мероприятие.</p>
        <? else: ?>
            </p>
        <? endif; ?>
		<p>Желаем Вам приятно провести время!</p>
	</div>
</div><?