<?
/** @var array $order */
/** @var array $orderItems */

?><!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Печать заказа № <?= $order['ID'] ?></title>
	<style rel="stylesheet">
		body {margin: 0; padding: 0;
			font-family: "Times New Roman";
			font-size: 14px;}
		.bilet {display: table; width: 600px;
			border-bottom: 2px dashed #cdcccc;}
		.block {display: table-cell; vertical-align: middle; padding: 10px 0; }
		.line {display: table-row;  vertical-align: middle;}

		.vertikal {display: block;
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			-ms-transform: rotate(-90deg);
			transform: rotate(-90deg);}
		.css-padding {padding: 7px 0;}
		.css-center {text-align: center;}
		span {display: block;}
		b {font-size: 20px;}
	</style>
</head>
<body>
<?

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

	foreach ($cartIds as $cartId)
	{
		$item = $orderItems['ITEMS'][$cartId];

		?>
		<div class="bilet">
			<div class="block" style="width: 150px; padding-left: 30px; vertical-align: top;">
				<div class="line" ><span>№ <b style="font-size: 17px;"><?= $order['COMMENTS'] ?></b></span></div>
				<div class="line"><span><b style="font-size: 14px;">KB-<?= $cartId ?></b></span></div>
			</div>
			<div class="block css-center" style="width: 230px;">
				<div class="line"><span style="font-size: 22px; font-weight: bold; display: block; width: 230px;">www.kupibilet.online</span></div>
				<div class="line css-padding"><span class="css-padding" style="font-size: 22px;"><?= $run['DATE_S'] ?></span></div>
				<div class="line css-padding"><span class="css-padding"><b><?= $event['NAME'] ?></b></span></div>
				<div class="line css-padding"><span class="css-padding"><b><?= $hall['NAME'] ?></b></span></div>
			</div>
			<div class="block"  style="width: 150px; padding-left: 10px; vertical-align: text-top;">
				<div class="line css-center" style="font-size: 12px;"><span style="width: 150px;"><i style="font-size: 26px; line-height: 23px;">БИЛЕТ</i></span></div>
				<div class="line css-center"><span><b style="padding-top: 20px; display: block;
				"><?= $item['PROPS']['SECTOR'] ?></b></span></div>
				<div class="line"><span class="css-padding">Ряд: <b><?= $item['PROPS']['ROW'] ?></b></span></div>
				<div class="line"><span class="css-padding">Место: <b><?= $item['PROPS']['NUM'] ?></b></span></div>
				<div class="line"><span class="css-padding">Цена: <b><?= $item['PRICE'] ?> руб.</b></span></div>
			</div>
			<div class="block" style="width: 50px; border-left: 1px dashed #000"><i class="vertikal css-center">КОНТРОЛЬ<br><?= $order['COMMENTS'] ?></i></div>
		</div><?
	}
}
?>
</body>
</html>
