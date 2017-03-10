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
	if ($orderId)
		LocalRedirect('/personal/order/?id=' . $orderId);
}

if ($notEmpty)
	\Local\Sale\Cart::prolongReserve($cart['ITEMS']);

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

            <div class="engBox engContent">
                <div class="elBasket">
                    <div class="elBasket-top">
                        <div class="it-img">
                            <img src="">
                        </div>
                        <div class="it-body">
                            <div class="it-title"><a href="<?= $event['DETAIL_PAGE_URL'] ?>"><?= $event['NAME'] ?></a></div>
                            <div class="it-date">
                                <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                                    <path style="line-height:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000;text-transform:none;block-progression:tb;isolation:auto;mix-blend-mode:normal" d="M 12 0 C 10.906937 0 10 0.9069372 10 2 L 10 4 L 4 4 C 3.4777778 4 2.9453899 4.1913289 2.5683594 4.5683594 C 2.1913289 4.9453899 2 5.4777778 2 6 L 2 46 C 2 46.522222 2.1913289 47.05461 2.5683594 47.431641 C 2.9453899 47.808671 3.4777778 48 4 48 L 46 48 C 46.522222 48 47.05461 47.808671 47.431641 47.431641 C 47.808671 47.05461 48 46.522222 48 46 L 48 6 C 48 5.4777778 47.808671 4.9453899 47.431641 4.5683594 C 47.05461 4.1913289 46.522222 4 46 4 L 40 4 L 40 2 C 40 0.9069372 39.093063 0 38 0 L 36 0 C 34.906937 0 34 0.9069372 34 2 L 34 4 L 16 4 L 16 2 C 16 0.9069372 15.093063 0 14 0 L 12 0 z M 12 2 L 14 2 L 14 8 L 12 8 L 12 2 z M 36 2 L 38 2 L 38 8 L 36 8 L 36 2 z M 4 6 L 10 6 L 10 8 C 10 9.0930628 10.906937 10 12 10 L 14 10 C 15.093063 10 16 9.0930628 16 8 L 16 6 L 34 6 L 34 8 C 34 9.0930628 34.906937 10 36 10 L 38 10 C 39.093063 10 40 9.0930628 40 8 L 40 6 L 46 6 L 46 13 L 4 13 L 4 6 z M 4 15 L 46 15 L 46 46 L 4 46 L 4 15 z M 17.984375 18.986328 A 1.0001 1.0001 0 0 0 17.839844 19 L 10 19 L 10 20 L 10 26.832031 A 1.0001 1.0001 0 0 0 10 27.158203 L 10 33.832031 A 1.0001 1.0001 0 0 0 10 34.158203 L 10 42 L 17.832031 42 A 1.0001 1.0001 0 0 0 18.158203 42 L 24.832031 42 A 1.0001 1.0001 0 0 0 25.158203 42 L 30 42 L 31.832031 42 A 1.0001 1.0001 0 0 0 32.158203 42 L 40 42 L 40 34.167969 A 1.0001 1.0001 0 0 0 40 33.841797 L 40 27.167969 A 1.0001 1.0001 0 0 0 40 26.841797 L 40 19 L 32.154297 19 A 1.0001 1.0001 0 0 0 31.984375 18.986328 A 1.0001 1.0001 0 0 0 31.839844 19 L 25.154297 19 A 1.0001 1.0001 0 0 0 24.984375 18.986328 A 1.0001 1.0001 0 0 0 24.839844 19 L 18.154297 19 A 1.0001 1.0001 0 0 0 17.984375 18.986328 z M 12 21 L 17 21 L 17 26 L 12 26 L 12 21 z M 19 21 L 24 21 L 24 25 A 1.0001 1.0001 0 1 0 26 25 L 26 21 L 31 21 L 31 25 A 1.0001 1.0001 0 1 0 33 25 L 33 21 L 38 21 L 38 26 L 34 26 A 1.0001 1.0001 0 1 0 34 28 L 38 28 L 38 33 L 34 33 A 1.0001 1.0001 0 1 0 34 35 L 38 35 L 38 40 L 33 40 L 33 36 A 1.0001 1.0001 0 0 0 31.984375 34.986328 A 1.0001 1.0001 0 0 0 31 36 L 31 40 L 30 40 L 26 40 L 26 36 A 1.0001 1.0001 0 0 0 24.984375 34.986328 A 1.0001 1.0001 0 0 0 24 36 L 24 40 L 19 40 L 19 35 L 23 35 A 1.0001 1.0001 0 1 0 23 33 L 19 33 L 19 28 L 23 28 A 1.0001 1.0001 0 1 0 23 26 L 19 26 L 19 21 z M 25.990234 26.990234 A 1.0001 1.0001 0 0 0 25.292969 28.707031 L 27.085938 30.5 L 25.292969 32.292969 A 1.0001 1.0001 0 1 0 26.707031 33.707031 L 28.5 31.914062 L 30.292969 33.707031 A 1.0001 1.0001 0 1 0 31.707031 32.292969 L 29.914062 30.5 L 31.707031 28.707031 A 1.0001 1.0001 0 0 0 30.980469 26.990234 A 1.0001 1.0001 0 0 0 30.292969 27.292969 L 28.5 29.085938 L 26.707031 27.292969 A 1.0001 1.0001 0 0 0 25.990234 26.990234 z M 12 28 L 17 28 L 17 33 L 12 33 L 12 28 z M 12 35 L 17 35 L 17 40 L 12 40 L 12 35 z"></path>
                                </svg>
                                <a href="<?= $runHref ?>"><?= $run['DATE_S'] ?></a></div>
                            <div class="it-zal">
                                <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M 12 0.03125 L 11.46875 0.34375 L 0.46875 7.15625 L 0 7.4375 L 0 8 L 0 21 L 0 22 L 1 22 L 23 22 L 24 22 L 24 21 L 24 8 L 24 7.4375 L 23.53125 7.15625 L 12.53125 0.34375 L 12 0.03125 z M 12 2.375 L 22 8.5625 L 22 20 L 2 20 L 2 8.5625 L 12 2.375 z M 8.5 9 C 7.1192881 9 6 10.119288 6 11.5 C 6 12.880712 7.1192881 14 8.5 14 C 9.8807119 14 11 12.880712 11 11.5 C 11 10.119288 9.8807119 9 8.5 9 z M 15.5 9 C 14.119288 9 13 10.119288 13 11.5 C 13 12.880712 14.119288 14 15.5 14 C 16.880712 14 18 12.880712 18 11.5 C 18 10.119288 16.880712 9 15.5 9 z M 6.6875 14.5 C 5.5875 14.8 4 16 4 18 L 13 18 L 20 18 C 20 16 18.4125 14.8 17.3125 14.5 C 16.8125 14.8 16.2 15 15.5 15 C 14.8 15 14.1875 14.8 13.6875 14.5 C 13.157842 14.644452 12.527069 15.011884 12 15.5625 C 11.472931 15.011884 10.842158 14.644452 10.3125 14.5 C 9.8125 14.8 9.2 15 8.5 15 C 7.8 15 7.1875 14.8 6.6875 14.5 z"></path>
                                </svg>
                                <a href="<?= $hall['DETAIL_PAGE_URL'] ?>"><?= $hall['NAME'] ?></a></div>
                        </div>
                    </div>
                    <div class="elBasket-body">
                        <div class="it-block">
                            <div class="it-sec">Секция</div>
                            <div class="it-rad">Ряд</div>
                            <div class="it-mest">Место</div>
                            <div class="it-price">Цена</div>
                            <div class="it-sbor">Серв.сбор</div>
                            <div class="it-sum">Сумма</div>
                            <div class="it-delete">Удалить</div>
                        </div>

                        <?
                        $total = 0;
                        $totalServ = 0;
                        foreach ($cartIds as $cartId)
                        {
                            $item = $cart['ITEMS'][$cartId];
                            $serv = floor($item['PRICE'] * SERVICE_CHARGE / 100);
                            $totalServ += $serv;
                            $totalServ += $item['PRICE'];
                            ?>

                            <div class="it-block" id="<?= $cartId ?>>
                                <div class="it-sec"><?= $item['PROPS']['SECTOR'] ?></div>
                                <div class="it-rad"><?= $item['PROPS']['ROW'] ?></div>
                                <div class="it-mest"><?= $item['PROPS']['NUM'] ?></div>
                                <div class="it-price"><p>Цена</p>><?= $item['PRICE'] ?> руб.</div>
                                <div class="it-sbor"><p>Серв.сбор</p><?= $serv ?> руб.</div>
                                <div class="it-sum"><p>Сумма</p><?= $item['PRICE'] + $serv ?> руб.</div>
                                <div class="it-delete">
                                <span class="delete">
                                    <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                        <path d="M21.736,19.64l-2.098,2.096c-0.383,0.386-1.011,0.386-1.396,0l-5.241-5.239L7.76,21.735 c-0.385,0.386-1.014,0.386-1.397-0.002L4.264,19.64c-0.385-0.386-0.385-1.011,0-1.398L9.505,13l-5.24-5.24 c-0.384-0.387-0.384-1.016,0-1.398l2.098-2.097c0.384-0.388,1.013-0.388,1.397,0L13,9.506l5.242-5.241 c0.386-0.388,1.014-0.388,1.396,0l2.098,2.094c0.386,0.386,0.386,1.015,0.001,1.401L16.496,13l5.24,5.241 C22.121,18.629,22.121,19.254,21.736,19.64z"></path>
                                    </svg>
                                </span>
                                </div>
                            </div>
                            <?
                        }
                        ?>
                    </div>
                    <div class="elBasket-footer">
                        <div class="it-block">
                            <div class="it-title">Вы выбрали 1 <b>билет</b> стоимостью</div>
                            <div class="it-value"><b>4 100 руб.</b></div>
                        </div>
                        <div class="it-block">
                            <div class="it-title">Сервисный сбор составит</div>
                            <div class="it-value"><b>0 руб.</b></div>
                        </div>
                        <div class="it-full">
                            Итого к оплате - 4 100 руб.
                        </div>
                    </div>
                    <div class="elBasket-form">
                        <div class="it-title">Куда и кому отправлять билет?</div>
                        <form action="/personal/order/" method="post">
                            <input type="text" name="order_name" placeholder="Имя (*)">
                            <input type="text" name="order_surname"  placeholder="Фамилия (*)">
                            <input type="text" name="order_email"  placeholder="E-mail (*)">
                            <input type="submit" name="order_create" value="Создать заказ">
                        </form>
                    </div>
                </div>
            </div>


<!--			<div class="run">-->
<!--				<hr />-->
<!--				<p>Блок показа (может быть несколько)</p>-->
<!--				<p>Дата: --><?//= $run['DATE_S'] ?><!-- <a href="--><?//= $runHref ?><!--">Ссылка</a></p>-->
<!--				<p>Событие: --><?//= $event['NAME'] ?><!-- <a href="--><?//= $event['DETAIL_PAGE_URL'] ?><!--">Ссылка</a></p>-->
<!--				<p>Зал: --><?//= $hall['NAME'] ?><!-- <a href="--><?//= $hall['DETAIL_PAGE_URL'] ?><!--">Ссылка</a></p>-->
<!---->
<!--				<table>-->
<!--					<thead>-->
<!--						<tr>-->
<!--							<th>Секция</th>-->
<!--							<th>Ряд</th>-->
<!--							<th>Место</th>-->
<!--							<th>Цена</th>-->
<!--							<th>Серв.сбор</th>-->
<!--							<th>Сумма</th>-->
<!--							<th>Удалить</th>-->
<!--						</tr>-->
<!--					</thead>-->
<!--					<tbody>--><?//
//						$total = 0;
//						$totalServ = 0;
//						foreach ($cartIds as $cartId)
//						{
//							$item = $cart['ITEMS'][$cartId];
//							$serv = floor($item['PRICE'] * SERVICE_CHARGE / 100);
//							$totalServ += $serv;
//							$totalServ += $item['PRICE'];
//							?>
<!--							<tr id="--><?//= $cartId ?><!--">-->
<!--								<td>--><?//= $item['PROPS']['SECTOR'] ?><!--</td>-->
<!--								<td>--><?//= $item['PROPS']['ROW'] ?><!--</td>-->
<!--								<td>--><?//= $item['PROPS']['NUM'] ?><!--</td>-->
<!--								<td>--><?//= $item['PRICE'] ?><!-- руб.</td>-->
<!--								<td>--><?//= $serv ?><!-- руб.</td>-->
<!--								<td>--><?//= $item['PRICE'] + $serv ?><!-- руб.</td>-->
<!--								<td><span class="delete">X</span></td>-->
<!--							</tr>--><?//
//						}
//						?>
<!--					</tbody>-->
<!--				</table>-->
<!--			</div>--><?//
		}

		?>
	</div>

	<form action="" method="post">
		<input type="text" name="order_name" />
		<input type="text" name="order_surname" />
		<input type="text" name="order_email" />
		<input type="submit" name="order_create" value="Создать заказ" />
	</form>

	<?
}