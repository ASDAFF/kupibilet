<?
namespace Local\Sale;

use Bitrix\Main\Loader;
use Bitrix\Sale\Compatible\BasketCompatibility;
use Local\Main\Event;
use Local\Main\Hall;
use Local\Main\Run;

/**
 * Class Cart Корзина
 * @package Local\Sale
 */
class Cart
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Sale/Cart/';

	/**
	 * Возвращает корзину текущего пользователя
	 * @return array
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function getCart()
	{
		$return = array(
			'COUNT' => 0,
			'PRICE' => 0,
			'SERV_PRICE' => 0,
		    'ITEMS' => array(),
		);
		Loader::IncludeModule('sale');

		$basket = new \CSaleBasket();
		$basket->Init();
		$rsCart = $basket->GetList(array(), array(
			'ORDER_ID' => 'NULL',
			'FUSER_ID' => $basket->GetBasketUserID(),
		));
		$ids = array();
		while ($item = $rsCart->Fetch())
		{
			$id = intval($item['ID']);
			$price = intval($item['PRICE']);
			$serv = floor($price * SERVICE_CHARGE / 100);
			$return['ITEMS'][$id] = array(
				'ID' => $id,
				'RUN' => intval($item['PRODUCT_ID']),
				'EVENT' => intval($item['PRODUCT_XML_ID']),
				'SIT' => intval($item['CATALOG_XML_ID']),
			    'DETAIL_PAGE_URL' => $item['DETAIL_PAGE_URL'],
			    'PRICE' => $price,
			    'SERV' => $serv,
			);

			$return['COUNT']++;
			$return['PRICE'] += $price;
			$return['SERV_PRICE'] += $serv;

			$ids[] = $id;
		}

		if ($ids)
		{
			$rsProps = $basket->GetPropsList(Array(), Array("@BASKET_ID" => $ids));
			while ($prop = $rsProps->Fetch())
			{
				$id = $prop['BASKET_ID'];
				$return['ITEMS'][$id]['PROPS'][$prop['CODE']] = $prop['VALUE'];
			}
		}

		return $return;
	}

	/**
	 * Возвращает сводку по корзине
	 */
	public static function getSummaryDB()
	{
		$cart = self::getCart();
		return array(
			'COUNT' => $cart['COUNT'],
			'PRICE' => $cart['PRICE'],
		);
	}

	/**
	 * Обновляет сводку по корзине
	 */
	public static function updateSessionCartSummary()
	{
		$_SESSION['CART_SUMMARY'] = self::getSummaryDB();
	}

	/**
	 * Возвращает сводку по корзине
	 */
	public static function getSummary()
	{
		if (!isset($_SESSION['CART_SUMMARY']))
			self::updateSessionCartSummary();

		return $_SESSION['CART_SUMMARY'];
	}

	/**
	 * Для текущего показа возвращает места, которые уже есть в корзине
	 * @param $runId
	 * @return array
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function getSitsByRun($runId)
	{
		$return = array();
		Loader::IncludeModule('sale');

		$basket = new \CSaleBasket();
		$basket->Init();
		$rsCart = $basket->GetList(array(), array(
			'ORDER_ID' => 'NULL',
			'FUSER_ID' => $basket->GetBasketUserID(),
			'PRODUCT_ID' => $runId,
		));
		while ($item = $rsCart->Fetch())
		{
			$id = intval($item['CATALOG_XML_ID']);
			$return[$id] = $item['ID'];
		}

		return $return;
	}

	/**
	 * Добавление товара (выбранное место) в корзину
	 * @param $id
	 * @param $eventId
	 * @param $runId
	 * @return bool|int
	 */
	public static function add($id, $eventId, $runId)
	{

		$id = intval($id);
		if ($id <= 0)
			return false;

		$event = Event::getById($eventId);
		if (!$event)
			return false;

		$run = Run::getById($runId);
		if (!$run)
			return false;

		$price = $run['PRICES'][$id];
		if (!$price)
			return false;

		$hall = Hall::getById($event['PRODUCT']['HALL']);

		$scheme = json_decode($hall['SCHEME'], true);
		$sit = $scheme[$id];
		if (!$sit)
			return false;

		Loader::IncludeModule('sale');

		$sits = self::getSitsByRun($runId);
		if ($sits[$id])
			return false;
		
		$props = array();
		$props[] = array(
			'NAME' => 'ID Места',
			'CODE' => 'SIT',
			'VALUE' => $id,
		);
		$props[] = array(
			'NAME' => 'Сектор',
			'CODE' => 'SECTOR',
			'VALUE' => $sit[3],
		);
		$props[] = array(
			'NAME' => 'Ряд',
			'CODE' => 'ROW',
			'VALUE' => $sit[4],
		);
		$props[] = array(
			'NAME' => 'Место',
			'CODE' => 'NUM',
			'VALUE' => $sit[5],
		);
		$name = $event['NAME'] . ' ' . $run['DATE_S'] .  ' ' . $sit[3] . ', ряд: ' . $sit[4] . ', место: ' . $sit[5];

		$fields = array(
			'PRODUCT_ID' => $runId,
			'PRICE' => $price,
			'CATALOG_XML_ID' => $id,
			'PRODUCT_XML_ID' => $eventId,
			'CURRENCY' => 'RUB',
			'QUANTITY' => 1,
			'LID' => SITE_ID,
			'DELAY' => 'N',
			'CAN_BUY' => 'Y',
			'NAME' => $name,
			'MODULE' => 'main',
			'DETAIL_PAGE_URL' => $event['DETAIL_PAGE_URL'] . $run['FURL'],
		    'PROPS' => $props,
		);

		$basket = new \CSaleBasket();
		$basket->Init();
		if (!$basket->CheckFields('ADD', $fields))
			return false;

		$basketItem = BasketCompatibility::add($fields);
		if (!$basketItem)
			return false;

		$ID = $basketItem->getId();

		if ($ID)
			self::updateSessionCartSummary();

		return $ID;
	}

	/**
	 * Удаление товара из корзины
	 * @param $id
	 * @param $eventId
	 * @param $runId
	 * @return bool|int
	 */
	public static function remove($id, $eventId, $runId)
	{

		$id = intval($id);
		if ($id <= 0)
			return false;

		$event = Event::getById($eventId);
		if (!$event)
			return false;

		$run = Run::getById($runId);
		if (!$run)
			return false;

		$sits = self::getSitsByRun($runId);
		$cartId = $sits[$id];
		if (!$cartId)
			return false;

		return self::delete($cartId);
	}

	/**
	 * Удаление товара из корзины
	 * @param $cartId
	 * @return bool|int
	 */
	public static function delete($cartId)
	{
		Loader::IncludeModule('sale');

		$basket = new \CSaleBasket();
		$basket->Init();
		$res = BasketCompatibility::delete($cartId);
		$return = $res->isSuccess();

		if ($return)
			self::updateSessionCartSummary();

		return $return;
	}

	public static function createOrder($cart)
	{
		Loader::IncludeModule('sale');

		$user = new \CUser();
		$userId = intval($user->GetID());

		$fields = array(
			'LID' => SITE_ID,
			'PERSON_TYPE_ID' => 1,
			'PAYED' => 'N',
			'CANCELED' => 'N',
			'STATUS_ID' => 'N',
			'PRICE' => $cart['PRICE'] + $cart['SERV_PRICE'],
			'CURRENCY' => 'RUB',
			'USER_ID' => $userId,
			'PAY_SYSTEM_ID' => 1,
			'DELIVERY_ID' => 2,
		);

		$order = new \CSaleOrder();
		$basket = new \CSaleBasket();
		$orderId = $order->Add($fields);

		if ($orderId)
		{
			foreach ($cart['ITEMS'] as $item)
			{
				$basket->Update($item['ID'], array(
					'ORDER_ID' => $orderId,
				));
				$basket->Add(array(
					'ORDER_ID' => $orderId,
					'PRODUCT_ID' => 1,
					'PRICE' => $cart['SERV_PRICE'],
					'CURRENCY' => 'RUB',
					'QUANTITY' => 1,
					'LID' => SITE_ID,
					'DELAY' => 'N',
					'CAN_BUY' => 'Y',
					'NAME' => 'Сервисный сбор',
					'MODULE' => 'main',
				));
			}

			self::updateSessionCartSummary();
		}

		return $orderId;
	}

	public static function getOrderById($id)
	{
		Loader::IncludeModule('sale');

		$user = new \CUser();
		$userId = intval($user->GetID());

		$order = new \CSaleOrder();
		$rsOrder = $order->GetList(array(), array(
			'ID' => $id,
			'USER_ID' => $userId,
		));
		$order = $rsOrder->Fetch();

		return $order;
	}
}