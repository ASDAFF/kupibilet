<?
/** @var array $order */
/** @var array $orderItems */

?>
<p>Проверочный код: <?= $order['COMMENTS'] ?></p><?

// Распределяем билеты по показам
$byRun = array();
foreach ($orderItems['ITEMS'] as $item)
{
	// Сервисный сбор
	if ($item['RUN'] == 1)
		continue;

	$byRun[$item['RUN']][] = $item['ID'];
}

foreach ($byRun as $runId => $cartIds)
{
	$run = \Local\Main\Run::getById($runId);
	$event = \Local\Main\Event::getById($run['EVENT']);
	$hall = \Local\Main\Hall::getById($event['PRODUCT']['HALL']);
	?>
	<div class="run">
	<hr />
	<p>Блок показа (может быть несколько)</p>
	<p>Дата: <?= $run['DATE_S'] ?> </p>
	<p>Событие: <?= $event['NAME'] ?></p>
	<p>Зал: <?= $hall['NAME'] ?></p>

	<table>
		<thead>
		<tr>
			<th>Секция</th>
			<th>Ряд</th>
			<th>Место</th>
			<th>Цена</th>
		</tr>
		</thead>
		<tbody><?
		$total = 0;
		$totalServ = 0;
		foreach ($cartIds as $cartId)
		{
			$item = $orderItems['ITEMS'][$cartId];
			$serv = floor($item['PRICE'] * SERVICE_CHARGE / 100);
			$totalServ += $serv;
			$totalServ += $item['PRICE'];
			?>
			<tr id="<?= $cartId ?>">
				<td><?= $item['PROPS']['SECTOR'] ?></td>
				<td><?= $item['PROPS']['ROW'] ?></td>
				<td><?= $item['PROPS']['NUM'] ?></td>
				<td><?= $item['PRICE'] ?> руб.</td>
			</tr><?
		}
		?>
		</tbody>
	</table>
	</div><?
}
