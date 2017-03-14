<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs('/js/cart.js');

$cart = \Local\Sale\Cart::getCart();
$notEmpty = $cart['COUNT'] > 0;

/** @var array $arParams */
if ($notEmpty && isset($_POST['order_create']))
{
	$user = \Local\System\User::checkOrder(
		$_REQUEST['order_name'],
		$_REQUEST['order_lastname'],
		$_REQUEST['order_email']
	);
	if ($user['ID'])
	{
		$orderId = \Local\Sale\Cart::createOrder($cart, $user);
		if ($orderId)
			LocalRedirect('/personal/order/?id=' . $orderId);
	}
}
else
	$user = \Local\System\User::getCurrentUser();

if ($notEmpty)
	\Local\Sale\Cart::prolongReserve($cart['ITEMS']);

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
	$byRun = array();
	foreach ($cart['ITEMS'] as $item)
	{
		$byRun[$item['RUN']][] = $item['ID'];
	}

	?>
	<div class="engBox engContent">
	<div class="elBasket"><?

		foreach ($byRun as $runId => $cartIds)
		{
			$run = \Local\Main\Run::getById($runId);
			$event = \Local\Main\Event::getById($run['EVENT']);
			$hall = \Local\Main\Hall::getById($event['PRODUCT']['HALL']);
			$runHref = $event['DETAIL_PAGE_URL'] . $run['FURL'];

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

		$tickets = \Local\System\Utils::cardinalNumberRus($cart['COUNT'], 'билетов', 'билет', 'билета');
		$tickets = $cart['COUNT'] . ' ' . $tickets;
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
			<div class="it-full">
				Итого к оплате - <span id="js-total"><?= ($cart['PRICE'] + $cart['SERV_PRICE']) ?></span> руб.
			</div>
		</div><?

		$order_name = $_REQUEST['order_name'];
		$order_lastname = $_REQUEST['order_lastname'];
		$order_email = $_REQUEST['order_email'];
		if ($user)
		{
			if (!$order_name)
				$order_name = $user['NAME'];
			if (!$order_lastname)
				$order_lastname = $user['LAST_NAME'];
			if (!$order_email)
				$order_email = $user['EMAIL'];
		}

		?>
		<div class="elBasket-form">
			<div class="it-title">Куда и кому отправлять билет?</div><?

			if ($user['MESSAGE'])
			{
				?>
				<p><?= $user['MESSAGE'] ?></p><?
			}

			?>
			<form action="/personal/cart/" method="post">
				<input type="text" name="order_name" placeholder="Имя" value="<?= $order_name ?>" />
				<input type="text" name="order_lastname"  placeholder="Фамилия" value="<?= $order_lastname ?>" />
				<input type="text" name="order_email"  placeholder="E-mail (*)" value="<?= $order_email ?>" />
				<input type="submit" name="order_create" value="ОПЛАТИТЬ">
			</form>
		</div>
	</div>
	</div><?

}
