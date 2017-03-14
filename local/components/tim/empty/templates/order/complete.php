<?
/** @var array $order */
/** @var array $orderItems */

?>
<div class="engBox">
    <div class="elFormAuth"><?

		if ($order['STATUS_ID'] == 'F')
		{
			?>
			<p>Заказ уже оплачен</p>
			<p><a href="/personal/order/print/?id=<?= $order['ID'] ?>">Распечатать</a></p><?
		}
	    elseif ($order['STATUS_ID'] == 'O')
	    {
		    ?>
		    <p>Заказ просрочен</p><?
	    }
		else
		{
			\Local\Sale\Cart::prolongReserve($orderItems['ITEMS']);

			?>
			<p>Заказ №<?= $order['ID'] ?></p>
			<p>Спасибо, Ваш заказ оформлен и забронирован!</p>
			<p>Бронь необходимо оплатить в течение 20 минут, по истечении данного времени она аннулируется.</p>
			<form action="/personal/order/payment/" method="post">
				<input type="submit" value="Оплатить заказ" />
				<input type="hidden" name="id" value="<?= $order['ID'] ?>" />
			</form><?
		}

	    ?>
    </div>
</div><?