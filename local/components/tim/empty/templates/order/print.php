<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Печать заказа № <?= $order['ID'] ?></title>
    <style rel="stylesheet">
        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans";
            font-size: 12px;
        }

        .bilet {
            width: 600px;
            border-bottom: 2px dashed #cdcccc;
        }

        .bilet td {
            vertical-align: middle
        }

        .block {
            padding: 10px 0;
        }

        .vertikal {
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            transform: rotate(-90deg);
        }

        .css-padding {
            padding: 7px 0;
        }

        .css-center {
            text-align: center;
        }

        span {
            display: block;
        }

        b {
            font-size: 14px;
        }
    </style>
</head>
<body>
<?

// Распределяем билеты по показам
$byRun = [];
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
		$secret = $item['PROPS']['SECRET'];
		if (!$secret)
			$secret = $order['COMMENTS'];

		?>
        <table class="bilet">
            <tr>
                <td style="vertical-align: top; width: 150px;">
                    <div class=""><span>№ <b style="font-size: 14px;"><?= $secret ?></b></span></div>
                    <div style=""><span><b style="font-size: 11px;">KB-<?= $cartId ?></b></span></div>
                </td>
                <td style="width: 230px;">
                    <div class=""><span style="font-size: 18px; font-weight: bold; display: block; width: 230px;">www.kupibilet.online</span>
                    </div>
                    <div class="css-padding"><span class="css-padding"
                                                   style="font-size: 15px;"><?= $run['DATE_S'] ?></span></div>
                    <div class="css-padding"><span class="css-padding"><b style="font-size: 17px"><?= $event['NAME'] ?></b></span></div>
                    <div class="css-padding"><span class="css-padding"><b style="font-size: 17px"><?= $hall['NAME'] ?></b></span></div>
                </td>

                <td style="width: 150px; padding-left: 10px;">
                    <div class="css-center" style="font-size: 12px;"><span style="width: 150px;"><i
                                    style="font-size: 22px; line-height: 23px;">БИЛЕТ</i></span></div>
                    <div class="css-center"><span><b style="padding-top: 20px; display: block;
				"><?= $item['PROPS']['SECTOR'] ?></b></span></div>
                    <div class=""><span class="css-padding">Ряд: <b><?= $item['PROPS']['ROW'] ?></b></span></div>
                    <div class=""><span class="css-padding">Место: <b><?= $item['PROPS']['NUM'] ?></b></span></div>
                    <div class=""><span class="css-padding">Цена: <b><?= $item['PRICE'] ?> руб.</b></span></div>
                </td>

                <td style="border-left: 1px dashed #000; width: 80px;">
                    <div style=" font-size: 12px" class="block vertikal">
                        <div class="css-center">КОНТРОЛЬ</div>
                        <div class="css-center"><?= $secret ?></div>
                    </div>
                </td>
            </tr>
        </table>
		<?
	}
}
?>
</body>
</html>
