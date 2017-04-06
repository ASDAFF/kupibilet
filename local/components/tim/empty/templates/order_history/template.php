<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$orders = \Local\Sale\Cart::getHistory();
if (!count($orders))
{
	?>
	<div class="engBox">
	<div class="elFormAuth">
		<p>Вы ещё ничего не заказывали.</p>
	</div>
	</div><?
}
else
{

	?>
	<div class="engBox engContent">
	<div class="elBasket">
		<div class="elBasket-body">
			<div class="it-block">
				<div class="it-sec">№ заказа</div>
				<div class="it-rad">Дата создания</div>
				<div class="it-sum">Сумма</div>
				<div class="it-mest">Проверочный код</div>
				<div class="it-price">Статус</div>
				<div class="it-sbor">Действия</div>
			</div><?

			foreach ($orders as $order)
			{
				$status = '';
				$action = '';
				$href = '';
				$price = '';
				if ($order['STATUS_ID'] == 'N')
				{
					$status = 'Ожидает оплаты';
					$action = 'Оплатить';
					$href = '/personal/order/payment/?id=' . $order['ID'];
					$price = $order['PRICE'] . ' руб.';
				}
				elseif ($order['STATUS_ID'] == 'RS')
				{
					$status = 'Забронирован';
					$action = 'Оплатить';
					$href = '/personal/order/payment/?id=' . $order['ID'];
					$price = $order['PRICE'] . ' руб.';
				}
				elseif ($order['STATUS_ID'] == 'F')
				{
					$status = 'Оплачен';
					$action = 'Респечатать';
					$href = '/personal/order/print/?id=' . $order['ID'];
					$price = $order['PRICE'] . ' руб.';
				}
				elseif ($order['STATUS_ID'] == 'O')
				{
					$status = 'Просрочен';
					$action = '';
					$href = '';
				}
				?>
				<div class="it-block">
				<div class="it-sec"><?= $order['ID'] ?></div>
				<div class="it-rad"><?= $order['DATE_INSERT'] ?></div>
				<div class="it-sum"><?= $price ?></div>
				<div class="it-mest"><?= $order['COMMENTS'] ?></div>
				<div class="it-price"><?= $status ?></div>
				<div class="it-sbor"><?
					if ($href)
					{
						?><a href="<?= $href ?>"><?= $action ?></a><?
					}
					?></div>
				</div><?
			}
			?>
		</div>
	</div>
	</div><?
}