<?
/** @var array $order */
/** @var array $orderItems */

\Local\Sale\Cart::prolongReserve($orderItems['ITEMS']);

?>
<div class="engBox">
    <div class="elFormAuth">
		<p>Заказ №<?= $order['ID'] ?></p>
		<p>Спасибо, Ваш заказ оформлен и забронирован!</p>
		<p>Бронь необходимо оплатить в течение 20 минут, по истечении данного времени она аннулируется.</p>
        <form action="/personal/order/payment/" method="post">
            <div class="elFormYes">
                <label for="elFormYes">С договором <a href="/oferta/">оферты</a> согласен</label>
                <input type="checkbox" id="elFormYes" name="delivery">
            </div>
			<input id="elFormYesTo" type="submit" disabled="false" class="set-none"  value="Оплатить заказ" />
			<input type="hidden" name="id" value="<?= $order['ID'] ?>" />
		</form>
    </div>
</div><?