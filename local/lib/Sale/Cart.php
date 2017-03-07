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
	 * Возвращает сводку по корзине
	 */
	public static function getSummaryDB()
	{
		$count = 0;
		$price = 0;
		Loader::IncludeModule('sale');

		$basket = new \CSaleBasket();
		$basket->Init();
		$rsCart = $basket->GetList(array(), array(
			'ORDER_ID' => 'NULL',
			'FUSER_ID' => $basket->GetBasketUserID(),
		));
		while ($item = $rsCart->Fetch())
		{
			$count++;
			$price += intval($item['PRICE']);
		}

		return array(
			'COUNT' => $count,
			'PRICE' => $price,
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
		
		//$props = array();
		$xmlId = $eventId . '|' . $runId . '|' . $id;
		$name = $event['NAME'] . ' ' . $run['DATE_S'] .  ' ' . $sit[3] . ', ряд: ' . $sit[4] . ', место: ' . $sit[5];

		$fields = array(
			'PRODUCT_ID' => $xmlId,
			'PRICE' => $price,
			'PRICE_ID' => $id,
			'CATALOG_XML_ID' => $runId,
			'PRODUCT_XML_ID' => $eventId,
			'CURRENCY' => 'RUB',
			'QUANTITY' => 1,
			'LID' => SITE_ID,
			'DELAY' => 'N',
			'CAN_BUY' => 'Y',
			'NAME' => $name,
			'MODULE' => 'main',
			'DETAIL_PAGE_URL' => $event['DETAIL_PAGE_URL'] . $run['FURL'],
		    //'PROPS' => $props,
		);

		debugmessage($fields);

		$basket = new \CSaleBasket();
		$basket->Init();
		if (!$basket->CheckFields('ADD', $fields))
			return false;

		$basketItem = BasketCompatibility::add($fields);
		if (!$basketItem)
			return false;

		$ID = $basketItem->getId();

		self::updateSessionCartSummary();

		return $ID;
	}


}