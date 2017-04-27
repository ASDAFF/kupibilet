<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$ordersIds = [];
$items = [];
$eventsIds = [];
$cartsIds = [];

// Заказы
$orders = \Local\Sale\Cart::getHistory();
foreach ($orders as $order)
	$ordersIds[] = $order['ID'];

// Позиции
$cart = new \CSaleBasket();
$carts = $cart->GetList([], ['ORDER_ID' => $ordersIds]);
while ($item = $carts->Fetch())
{
	if ($item['PRODUCT_ID'] != 1)
	{
		$orders[$item['ORDER_ID']]['ITEMS'][$item['ID']] = $item;
		$cartsIds[] = $item['ID'];
	}
}

// Бронь
$reserved = \Local\Sale\Reserve::getByFilter(['UF_CART' => $cartsIds], true);
foreach ($orders as $key => $order)
{
	foreach ($order['ITEMS'] as $item)
	{
		if ($reserved[$item['ID']])
		{
			$orders[$key]['EXPIRED'] = $reserved[$item['ID']]['UF_EXPIRED'];
			break;
		}
	}
}

// Свойства позиций
$info = $cart->GetPropsList([], ['@BASKET_ID' => $cartsIds]);
$infoItems = [];
while ($infoItem = $info->Fetch())
	$infoItems[$infoItem['BASKET_ID']][$infoItem['CODE']] = $infoItem['VALUE'];


?><div class="engBox engContent">
<div class="elOrder"><?

	if (!count($orders))
	{
		?>
        <div class="elFormAuth">
            <p>Вы ещё ничего не заказывали.</p>
        </div><?
	}
	else
	{
		?>
        <div class="it-item">
            <div class="it-number">№</div>
            <div class="it-img">Мероприятия</div>
            <div class="it-name"></div>
            <div class="it-price">Сумма</div>
            <div class="it-status">Статус</div>
            <div class="it-time">Оставшееся время<br>для оплаты</div>
            <div class="it-actions">Действия</div>
            <div class="it-delete"></div>
        </div>
		<?

		foreach ($orders as $order)
		{
			$status = '';
			$action = '';
			$href = '';
			$price = '';
			$status_class = '';
			$delPossible = false;
			if ($order['STATUS_ID'] == 'N')
			{
				$status = 'Ожидает оплаты';
				$action = 'Оплатить';
				$href = '/personal/order/payment/?id=' . $order['ID'];
				$price = $order['PRICE'] . ' руб.';
				$delPossible = true;
			}
			elseif ($order['STATUS_ID'] == 'RS')
			{
				$status = 'Забронирован';
				$action = 'Оплатить';
				$href = '/personal/order/payment/?id=' . $order['ID'];
				$price = $order['PRICE'] . ' руб.';
				$delPossible = true;
			}
			elseif ($order['STATUS_ID'] == 'F')
			{
				$status = 'Оплачен';
				$status_class = 'set-green';
				$action = 'Распечатать';
				$href = '/personal/order/print/?id=' . $order['ID'];
				$price = $order['PRICE'] . ' руб.';
				if ($order['ALLOW_DELIVERY'] == 'Y')
				{
					$href = '';
					$action = '';
				}
			}
			elseif ($order['STATUS_ID'] == 'O')
			{
				$status = 'Просрочен';
				$status_class = 'set-red';
				$action = '';
				$href = '';
			}

			?>
            <div class="it-item set-zakaz" id="order-<?= $order['ID'] ?>" zakaz_id="<?= $order['ID'] ?>">
                <div class="it-number">№ <span><?= $order['ID'] ?></span></div>
                <div class="it-img"></div>
                <div class="it-name">
					<div class="it-name-inf">
                        <?= $order['DATE_INSERT'] ?>
                    </div>
                    <span>Подробнее</span>

                </div>
                <div class="it-price"><span><?= $order['PRICE'] ?> руб.</span></div>
                <div class="it-status">
                <span class="set-green">
                    <span class="<?= $status_class ?>"><?= $status ?></span>
                </span>
                </div>
                <div class="it-time"><?
	                if ($order['STATUS_ID'] == 'RS')
					{
						?>
                        Осталось: <span class="timer" data-expired="<?= $order['EXPIRED'] - time() ?>"
                                        id="<?= $item['ID'] ?>"></span><?
					}
	                ?>
                </div>
                <div class="it-actions">
                    <a href="<?= $href ?>" target="_blank"><?= $action ?></a>
                </div>
                <div class="it-delete"><?
	                if ($delPossible)
					{
						?>
                        <span class="delete" data-id="<?= $order['ID'] ?>">
	                        <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" width="26" height="26"
	                             viewBox="0 0 26 26">
	                            <path d="M21.736,19.64l-2.098,2.096c-0.383,0.386-1.011,0.386-1.396,0l-5.241-5.239L7.76,21.735 c-0.385,0.386-1.014,0.386-1.397-0.002L4.264,19.64c-0.385-0.386-0.385-1.011,0-1.398L9.505,13l-5.24-5.24 c-0.384-0.387-0.384-1.016,0-1.398l2.098-2.097c0.384-0.388,1.013-0.388,1.397,0L13,9.506l5.242-5.241 c0.386-0.388,1.014-0.388,1.396,0l2.098,2.094c0.386,0.386,0.386,1.015,0.001,1.401L16.496,13l5.24,5.241 C22.121,18.629,22.121,19.254,21.736,19.64z"></path>
	                        </svg>
	                    </span><?
					}
	                ?>
                </div>
            </div><?

			foreach ($order['ITEMS'] as $item)
			{
				$event = \Local\Main\Event::getById($item['PRODUCT_XML_ID']);
				$run = \Local\Main\Run::getById($item['PRODUCT_ID']);
				$info = $infoItems[$item['ID']];
				$reserve = $reserved[$item['ID']];

				$pic = $event['PREVIEW_PICTURE'];

				?>
	            <div class="it-item" id="item-order-<?= $order['ID'] ?>" zakaz_list_id="<?= $order['ID'] ?>">
	                <div class="it-number"></div>
	                <div class="it-img" style="
	                        background-image: url(<?= $pic ?>);
	                        filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?= $pic ?>');"></div>
	                <div class="it-name">
	                    <a href="<?= $event['DETAIL_PAGE_URL'] ?>" class="it-name-title"><?= $event['NAME'] ?></a>
		                <br />
	                    <div class="it-name-inf">
	                        Секция: <?= $info['SECTOR'] ?><br>
	                        Ряд: <b><?= $info['ROW'] ?></b> Место: <b><?= $info['NUM'] ?></b> <br>
	                        Время: <b><?= $run['DATE'] ?></b>
	                    </div>
	                </div>
	                <div class="it-price"><span><?= (float)$item['PRICE'] ?> руб.</span></div>
	                <div class="it-status"></div>
	                <div class="it-time"></div>
	                <div class="it-actions"></div>
	                <div class="it-delete"></div>
	            </div><?
			}
		}
	}
	?>
</div>
</div>