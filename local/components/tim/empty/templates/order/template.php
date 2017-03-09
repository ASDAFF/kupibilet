<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs('/js/cart.js');

$cart = \Local\Sale\Cart::getCart();
$notEmpty = $cart['COUNT'] > 0;

/** @var array $arParams */
if ($notEmpty && isset($_POST['order_create']))
{
	$orderId = \Local\Sale\Cart::createOrder($cart);
}

$emptyStyle = $notEmpty ? ' style="display:none;"' : '';

?>
<div class="empty-cart"<?= $emptyStyle ?>>
	Ваша корзина пуста
</div><?

if ($notEmpty)
{
	?>
	<div><?

		// Распределяем билеты по показам
		$byRun = array();
		foreach ($cart['ITEMS'] as $item)
		{
			$byRun[$item['RUN']][] = $item['ID'];
		}

		foreach ($byRun as $runId => $cartIds)
		{
			$run = \Local\Main\Run::getById($runId);
			$event = \Local\Main\Event::getById($run['EVENT']);
			$hall = \Local\Main\Hall::getById($event['PRODUCT']['HALL']);
			$runHref = $event['DETAIL_PAGE_URL'] . $run['FURL'];
			?>
			<div class="run">
				<hr />
				<p>Блок показа (может быть несколько)</p>
				<p>Дата: <?= $run['DATE_S'] ?> <a href="<?= $runHref ?>">Ссылка</a></p>
				<p>Событие: <?= $event['NAME'] ?> <a href="<?= $event['DETAIL_PAGE_URL'] ?>">Ссылка</a></p>
				<p>Зал: <?= $hall['NAME'] ?> <a href="<?= $hall['DETAIL_PAGE_URL'] ?>">Ссылка</a></p>

				<table>
					<thead>
						<tr>
							<th>Секция</th>
							<th>Ряд</th>
							<th>Место</th>
							<th>Цена</th>
							<th>Серв.сбор</th>
							<th>Сумма</th>
							<th>Удалить</th>
						</tr>
					</thead>
					<tbody><?
						$total = 0;
						$totalServ = 0;
						foreach ($cartIds as $cartId)
						{
							$item = $cart['ITEMS'][$cartId];
							$serv = floor($item['PRICE'] * SERVICE_CHARGE / 100);
							$totalServ += $serv;
							$totalServ += $item['PRICE'];
							?>
							<tr id="<?= $cartId ?>">
								<td><?= $item['PROPS']['SECTOR'] ?></td>
								<td><?= $item['PROPS']['ROW'] ?></td>
								<td><?= $item['PROPS']['NUM'] ?></td>
								<td><?= $item['PRICE'] ?> руб.</td>
								<td><?= $serv ?> руб.</td>
								<td><?= $item['PRICE'] + $serv ?> руб.</td>
								<td><span class="delete">X</span></td>
							</tr><?
						}
						?>
					</tbody>
				</table>
			</div><?
		}

		?>
	</div>

	<form action="/personal/order/" method="post">
		<input type="text" name="order_name" />
		<input type="text" name="order_surname" />
		<input type="text" name="order_email" />
		<input type="submit" name="order_create" value="Создать заказ" />
	</form>

	<?
}