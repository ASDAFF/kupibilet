<?
/** @var array $order */
/** @var array $orderItems */

$reserved_time = $order['STATUS_ID'] == 'RS' ? RESERVE_TIME_24_TEXT : RESERVE_TIME_TEXT;

?>
<div class="engBox">
    <div class="elFormAuth">
		<p>Заказ №<?= $order['ID'] ?></p>
		<p>Спасибо, Ваш заказ оформлен и забронирован!</p>
		<p>Бронь необходимо оплатить в течение <?= $reserved_time ?>, по истечении данного времени она аннулируется.</p>
        <form action="/personal/order/payment/" method="post">
            <div class="elFormYes">
                <label for="elFormYes">С договором <a target="_blank" href="/oferta/">оферты</a> и <a target="_blank" href="/agreement/private.php"> политикой конфиденциальности</a> согласен(на)</label>
                <input type="checkbox" id="elFormYes" name="delivery">
            </div>
			<input id="elFormYesTo" type="submit" disabled="false" class="set-none"  value="Оплатить заказ" />
			<input type="hidden" name="id" value="<?= $order['ID'] ?>" />
		</form>
    </div>
</div><?
