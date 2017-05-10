<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs('/js/cart.js');

$cart = \Local\Sale\Cart::getCart();
$notEmpty = $cart['COUNT'] > 0;

$deliveryPrice = 100;
$deliveryErrors = '';

if ($notEmpty && (isset($_POST['order_create']) || isset($_POST['order_reserve'])))
{
	$user = \Local\System\User::checkOrder(
		$_REQUEST['order_name'],
		$_REQUEST['order_lastname'],
		$_REQUEST['order_email'],
		$_REQUEST['order_phone'],
		$_REQUEST['order_address']
	);
	if ($_REQUEST['delivery'])
	{
		if (!$_REQUEST['order_phone'])
			$deliveryErrors .= 'Введите телефон<br/ >';
		if (!$_REQUEST['order_address'])
			$deliveryErrors .= 'Введите адрес<br/ >';
	}
	if ($user['ID'] && !$deliveryErrors)
	{
	    $status = (isset($_POST['order_reserve'])) ? 'RS' : 'N';

		$orderId = \Local\Sale\Cart::createOrder($cart, $user, $_REQUEST['delivery'] ? $deliveryPrice : 0, $status);
        $reserve_time = (isset($_POST['order_reserve'])) ? RESERVE_TIME_24 : RESERVE_TIME;
		\Local\Sale\Cart::prolongReserve($cart['ITEMS'], $reserve_time);
        if ($orderId)
			LocalRedirect('/personal/order/?id=' . $orderId);
	}
}
else
{
	$user = \Local\System\User::getCurrentUser();
	if ($notEmpty)
		\Local\Sale\Cart::prolongReserve($cart['ITEMS']);
}

$emptyStyle = $notEmpty ? ' style="display:none;"' : '';

?>
<div class="engBox engContent cssPadding">
    <div class="empty-cart"<?= $emptyStyle ?>>
        Ваша корзина пуста
    </div>
</div><?

if ($notEmpty)
{
	// Распределяем билеты по показам
	$byRun = [];
	foreach ($cart['ITEMS'] as $item)
	{
		$byRun[$item['RUN']][] = $item['ID'];
	}

	?>
	<div class="engBox engContent">
	<div class="elBasket"><?

		$reserve = true;
		$currentTime = time();
		foreach ($byRun as $runId => $cartIds)
		{

			$run = \Local\Main\Run::getById($runId);
			$event = \Local\Main\Event::getById($run['EVENT']);
			$hall = \Local\Main\Hall::getById($event['PRODUCT']['HALL']);
			$runHref = $event['DETAIL_PAGE_URL'] . $run['FURL'];

			//проверка возможности бронирования
			if ($reserve)
			{
				$endReserveTime = $run['TS'] - RESERVE_TIME_24_BEFORE;
				if ($currentTime >= $endReserveTime)
					$reserve = false;
			}

			?>
			<div class="js-run">
	            <div class="elBasket-top">
	                <div class="it-img" style="background-image: url(<?= $event['PREVIEW_PICTURE'] ?>);filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?= $event[PREVIEW_PICTURE] ?>');"></div>
	                <div class="it-body">
	                    <div class="it-title"><a href="<?= $event['DETAIL_PAGE_URL'] ?>"><?= $event['NAME'] ?></a></div>
	                    <div class="it-date">
                            <i class="engIcon setIcon-date-black"></i>
	                        <a href="<?= $runHref ?>"><?= $run['DATE_S'] ?></a></div>
	                    <div class="it-zal">
                            <i class="engIcon setIcon-map-black"></i>
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

	                    <div class="it-block js-row" id="<?= $cartId?>">
	                        <div class="it-sec"><?= $item['PROPS']['SECTOR'] ?></div>
	                        <div class="it-rad"><?= $item['PROPS']['ROW'] ?></div>
	                        <div class="it-mest"><?= $item['PROPS']['NUM'] ?></div>
	                        <div class="it-price"><p>Цена</p><?= $item['PRICE'] ?> руб.</div>
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
	        </div><?
		}

		$order_name = $_REQUEST['order_name'];
		$order_lastname = $_REQUEST['order_lastname'];
		$order_email = $_REQUEST['order_email'];
		$order_address = $_REQUEST['order_address'];
		$order_phone = $_REQUEST['order_phone'];
		if ($user)
		{
			if (!$order_name)
				$order_name = $user['NAME'];
			if (!$order_lastname)
				$order_lastname = $user['LAST_NAME'];
			if (!$order_email)
				$order_email = $user['EMAIL'];
			if (!$order_phone)
				$order_phone = $user['PHONE'];
			if (!$order_address)
				$order_address = $user['ADDRESS'];
		}

		$pole = $_REQUEST['delivery'] ? ' on' : '';

		?>
		<form action="/personal/cart/" method="post">
		<div class="elBasket-body elDost">
			<div class="it-block js-row" >
				<div class="it-sec set-70">
					<div class="elDost-top">
						<input type="checkbox" id="elDostId"
						       name="delivery"<?= $pole ? ' checked' : ''?>/>
						<label for="elDostId">Доставить билет на дом с курьером (только по КМВ)</label>
					</div>
					<div class="elDost-bottom elDostPole<?= $pole ?>"><?
						echo $deliveryErrors;
						?>
						<input type="text" name="order_address" placeholder="Введите адрес" value="<?= $order_address ?>">
						<input type="text" name="order_phone" placeholder="Введите Номер телефона" value="<?= $order_phone ?>">
					</div>
				</div>
				<div class="it-sum">
					<div class="elDostPole<?= $pole ?>">
						<p>Сумма</p><?= $deliveryPrice ?> руб.
					</div>
				</div>
				<div class="it-delete">
					<div class="elDostPole<?= $pole ?>">
                        <span class="delete">
                            <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
	                            <path d="M21.736,19.64l-2.098,2.096c-0.383,0.386-1.011,0.386-1.396,0l-5.241-5.239L7.76,21.735 c-0.385,0.386-1.014,0.386-1.397-0.002L4.264,19.64c-0.385-0.386-0.385-1.011,0-1.398L9.505,13l-5.24-5.24 c-0.384-0.387-0.384-1.016,0-1.398l2.098-2.097c0.384-0.388,1.013-0.388,1.397,0L13,9.506l5.242-5.241 c0.386-0.388,1.014-0.388,1.396,0l2.098,2.094c0.386,0.386,0.386,1.015,0.001,1.401L16.496,13l5.24,5.241 C22.121,18.629,22.121,19.254,21.736,19.64z"></path>
                            </svg>
                        </span>
					</div>
				</div>
			</div>
            <div class="it-block-text">
                Доставка билетов за пределы КМВ осуществляется по предварительной договоренности по телефону +7 (928) 335-65-65
            </div>
		</div><?

		$tickets = \Local\System\Utils::cardinalNumberRus($cart['COUNT'], 'билетов', 'билет', 'билета');
		$tickets = $cart['COUNT'] . ' ' . $tickets;
		$total = $cart['PRICE'] + $cart['SERV_PRICE'];
		if ($pole)
			$total += $deliveryPrice;

		?>
		<div class="elBasket-footer">
			<div class="it-block">
				<div class="it-title">Вы выбрали <b id="js-tickets"><?= $tickets ?></b> стоимостью</div>
				<div class="it-value"><b><span id="js-price"><?= $cart['PRICE'] ?></span> руб.</b></div>
			</div>
			<div class="it-block">
				<div class="it-title">Сервисный сбор составит</div>
				<div class="it-value"><b><span id="js-serv-price"><?= $cart['SERV_PRICE'] ?></span> руб.</b></div>
			</div>
			<div class="it-block js-delivery-block"<?= $pole ? '' : ' style="display:none;"'?>>
				<div class="it-title">Доставка</div>
				<div class="it-value"><b><span id="js-delivery"><?= $deliveryPrice ?></span> руб.</b></div>
			</div>
			<div class="it-full">
				Итого к оплате - <span id="js-total"><?= $total ?></span> руб.
			</div>
		</div><?

		?>
		<div class="elBasket-form">
			<div class="it-title">Куда и кому отправлять билет?</div><?

			if ($user['MESSAGE'])
			{
				?>
				<p><?= $user['MESSAGE'] ?></p><?
			}

			?>
			<input type="text" name="order_name" placeholder="Имя" value="<?= $order_name ?>" />
			<input type="text" name="order_lastname"  placeholder="Фамилия" value="<?= $order_lastname ?>" />
			<input type="text" name="order_email"  placeholder="E-mail (*)" value="<?= $order_email ?>" />
			<input type="submit" name="order_create" value="ОФОРМИТЬ ЗАКАЗ"><?
			if ($reserve)
			{
				?>
				<input type="submit" name="order_reserve" value="Забронировать на 24 часа"><?
			}
			?>
		</div>
		</form>
	</div>
	</div><?

}
