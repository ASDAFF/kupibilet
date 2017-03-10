<?
namespace Local\Main;

use Bitrix\Iblock\InheritedProperty\ElementValues;
use Local\System\ExtCache;

/**
 * Class Event События (мероприятия)
 * @package Local\Main
 */
class Event
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Event/';

	/**
	 * ID инфоблока
	 */
	const IBLOCK_ID = 3;

	/**
	 * Время кеширования
	 */
	const CACHE_TIME = 86400;

	/**
	 * Возвращает все мероприятия со свойствами, которые нужны для построения панели фильтров
	 * @param bool|false $refreshCache
	 * @return array
	 */
	public static function getAll($refreshCache = false)
	{
		$return = array();

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			static::CACHE_TIME
		);
		if(!$refreshCache && $extCache->initCache()) {
			$return = $extCache->getVars();
		} else {
			$extCache->startDataCache();

			$runs = Run::getSimple($refreshCache);

			$select = array(
				'ID',
				'NAME',
				'CODE',
				'PROPERTY_HALL',
				'PROPERTY_PRICE',
				'PROPERTY_PRICE_TO',
				'PROPERTY_GENRE',
                'PROPERTY_DATE',
                'PROPERTY_E_TICKET',
                'PROPERTY_AGE',
			);
			$flagsSelect = Flags::getForSelect();
			$select = array_merge($select, $flagsSelect);
			$codes = Flags::getCodes();

			$iblockElement = new \CIBlockElement();
			$rsItems = $iblockElement->GetList(array(), array(
				'IBLOCK_ID' => self::IBLOCK_ID,
				'ACTIVE' => 'Y',
			), false, false, $select);
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$gid = intval($item['PROPERTY_GENRE_VALUE']);
				$genre = Genre::getById($gid);

				$product = array(
					'ID' => $id,
					'NAME' => $item['NAME'],
					'CODE' => $item['CODE'],
					'HALL' => intval($item['PROPERTY_HALL_VALUE']),
					'GENRE' => intval($genre['ID']),
					'PRICE' => intval($item['PROPERTY_PRICE_VALUE']),
                    'PRICE_TO' => intval($item['PROPERTY_PRICE_TO_VALUE']),
                    'E_TICKET' => $item['PROPERTY_E_TICKET_VALUE'],
                    'AGE' => $item['PROPERTY_AGE_VALUE'],
					'RUNS' => $runs[$id],
				);

				foreach ($codes as $code)
					$product[$code] = intval($item['PROPERTY_' . $code . '_VALUE']);

				$return[$id] = $product;
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	/**
	 * Возвращает мероприятие по ID
	 * @param $id
	 */
	public static function getSimpleById($id)
	{
		$all = self::getAll();
		return $all[$id];
	}

	/**
	 * Возвращает данные по фильтру
	 * (сначала получает все getAll - потом фильтрует)
	 * @param $filter
	 * @param bool|false $refreshCache
	 * @return array
	 */
	public static function getDataByFilter($filter, $refreshCache = false)
	{
		$return = array(
			'COUNT' => 0,
		);

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
				$filter,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			static::CACHE_TIME
		);
		if(!$refreshCache && $extCache->initCache()) {
			$return = $extCache->getVars();
		} else {
			$extCache->startDataCache();

			$all = self::getAll($refreshCache);

			foreach ($all as $productId => $product)
			{
				$ok = true;
				foreach ($filter as $key => $value)
				{
					if ($key == 'ID')
					{
						if (!$value[$productId])
						{
							$ok = false;
							break;
						}
					}
					elseif ($key == 'HALL')
					{
						if ($product[$key] != $value)
						{
							$ok = false;
							break;
						}
					}
					elseif ($key == 'PRICE')
					{
						if (isset($value['FROM']) && $product['PRICE_TO'] < $value['FROM'] ||
							isset($value['TO']) && $product['PRICE'] > $value['TO'])
						{
							$ok = false;
							break;
						}
					}
					elseif ($key == 'DATE')
					{
						if (isset($value['FROM']) || isset($value['TO']) || isset($value['DAY']))
						{
							$ex = false;
							foreach ($product['RUNS'] as $ts)
							{
								if (isset($value['DAY']))
								{
									if ($ts >= $value['DAY'] && $ts <= $value['DAY'] + 86400)
									{
										$ex = true;
										break;
									}
								}
								elseif (!(isset($value['FROM']) && $ts < $value['FROM'] ||
									isset($value['TO']) && $ts >= $value['TO'] + 86400))
								{
									$ex = true;
									break;
								}
							}
							if (!$ex)
							{
								$ok = false;
								break;
							}
						}
					}
					elseif ($key == 'GENRE')
					{
						if (!$value[$product['GENRE']])
						{
							$ok = false;
							break;
						}
					}
					else
					{
						if (!$product[$key])
						{
							$ok = false;
							break;
						}
					}

				}

				if ($ok)
				{
					$return['COUNT']++;
					$return['IDS'][] = $product['ID'];

					if (!isset($return['PRICE']['MIN']) || $return['PRICE']['MIN'] > $product['PRICE'])
						$return['PRICE']['MIN'] = $product['PRICE'];
					if (!isset($return['PRICE']['MAX']) || $return['PRICE']['MAX'] < $product['PRICE_TO'])
						$return['PRICE_TO']['MAX'] = $product['PRICE_TO'];

					foreach ($product['RUNS'] as $ts)
					{
						if (!isset($return['DATE']['MIN']) || $return['DATE']['MIN'] > $ts)
							$return['DATE']['MIN'] = $ts;
						if (!isset($return['DATE']['MAX']) || $return['DATE']['MAX'] < $ts)
							$return['DATE']['MAX'] = $ts;
					}

					if (!isset($return['GENRE'][$product['GENRE']]))
						$return['GENRE'][$product['GENRE']] = 0;
					$return['GENRE'][$product['GENRE']]++;

					foreach (Flags::getCodes() as $code)
					{
						if ($product[$code])
						{
							if (!isset($return[$code]))
								$return[$code] = 0;
							$return[$code]++;
						}
					}
				}
			}

			if ($filter['ID'])
			{
				$ids = array();
				foreach ($return['IDS'] as $id)
					$ids[$id] = true;
				$res = array();
				foreach ($filter['ID'] as $id)
				{
					if ($ids[$id])
						$res[] = $id;
				}
				$return['IDS'] = $res;
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	/**
	 * Есть ли хоть одно мероприятие по фильтру?
	 * @param $filter
	 * @return bool
	 */
	public static function exByFilter($filter)
	{
		$all = self::getAll();
		foreach ($all as $productId => $product)
		{
			$ok = true;
			foreach ($filter as $key => $value)
			{
				if ($key == 'GENRE')
				{
					if (!$value[$product['GENRE']])
					{
						$ok = false;
						break;
					}
				}
				else
				{
					if (!$product[$key])
					{
						$ok = false;
						break;
					}
				}
			}

			if ($ok)
				return true;
		}

		return false;
	}

	/**
	 * Есть ли 3 мероприятия по фильтру?
	 * @param $filter
	 * @return bool
	 */
	public static function ex3ByFilter($filter)
	{
		$all = self::getAll();
		$cnt = 0;
		foreach ($all as $productId => $product)
		{
			$ok = true;
			foreach ($filter as $key => $value)
			{
				if ($key == 'GENRE')
				{
					if (!$value[$product['GENRE']])
					{
						$ok = false;
						break;
					}
				}
				else
				{
					if (!$product[$key])
					{
						$ok = false;
						break;
					}
				}
			}

			if ($ok)
			{
				$cnt++;
				if ($cnt >= 3)
					return true;
			}
		}

		return false;
	}

	/**
	 * Возвращает мероприятия по фильтру. Сначала получаем айдишники товаров методом getSimpleByFilter
	 * Результат уже должен быть закеширован (панелью фильтров)
	 * @param $sort
	 * @param $productIds
	 * @param $nav
	 * @param bool|false $refreshCache
	 * @return array
	 */
	public static function getByFilter($sort, $productIds, $nav, $refreshCache = false)
	{
		$return = array();

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
				$sort,
				$productIds,
				$nav,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			static::CACHE_TIME
		);
		if(!$refreshCache && $extCache->initCache()) {
			$return = $extCache->getVars();
		} else {
			$extCache->startDataCache();

			if ($productIds || $nav['nTopCount'])
			{
				$return['NAV'] = array(
					'COUNT' => count($productIds),
					'PAGE' => $nav['iNumPage'],
				);

				// В случае поиска - ручная пагинация
				if ($sort['SEARCH'] == 'asc' && $nav)
				{
					$l = $nav['nPageSize'];
					$offset = ($nav['iNumPage'] - 1) * $l;
					$productIds = array_slice($productIds, $offset, $l);
					$nav = false;
				}

				if (!isset($sort['ID']))
					$sort['ID'] = 'DESC';

				$filter = array(
					'IBLOCK_ID' => self::IBLOCK_ID,
				    'ACTIVE' => 'Y',
				);
				if ($productIds)
					$filter['=ID'] = $productIds;

				// Товары
				$iblockElement = new \CIBlockElement();
				$rsItems = $iblockElement->GetList($sort, $filter, false, $nav, array(
					'ID', 'NAME', 'CODE',
					'PREVIEW_PICTURE',
				));
				while ($item = $rsItems->GetNext())
				{
					$product = self::getSimpleById($item['ID']);

					$ipropValues = new ElementValues(self::IBLOCK_ID, $item['ID']);
					$iprop = $ipropValues->getValues();

					$detail = self::getDetailUrl($product);

					$product['NAME'] = $item['NAME'];
					$product['PIC_ALT'] = $iprop['ELEMENT_PREVIEW_PICTURE_FILE_ALT'] ?
						$iprop['ELEMENT_PREVIEW_PICTURE_FILE_ALT'] : $item['NAME'];
					$product['PIC_TITLE'] = $iprop['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] ?
						$iprop['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] : $item['NAME'];
					$product['DETAIL_PAGE_URL'] = $detail;
					$product['PREVIEW_PICTURE'] = \CFile::GetPath($item['PREVIEW_PICTURE']);

					$runs = Run::getByEvent($item['ID']);
					$product['RUNS'] = $runs;

					$return['ITEMS'][$item['ID']] = $product;
				}

				// Восстановление сортировки для поиска
				if ($sort['SEARCH'] == 'asc')
				{
					$items = array();
					foreach ($productIds as $id)
					{
						if ($return['ITEMS'][$id])
							$items[$id] = $return['ITEMS'][$id];
					}
					$return['ITEMS'] = $items;
				}
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	public static function getByHall($hallId)
	{
		$filter = array(
			'HALL' => $hallId,
		);
		$data = self::getDataByFilter($filter);
		$events = Event::getByFilter(array(), $data['IDS'], false);
		return $events['ITEMS'];
	}

	/**
	 * Возвращает ID мероприятия по коду
	 * @param $code
	 * @param bool|false $refreshCache
	 * @return int|mixed
	 */
	public static function getIdByCode($code, $refreshCache = false)
	{
		$return = 0;

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
				$code,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			static::CACHE_TIME
		);
		if(!$refreshCache && $extCache->initCache()) {
			$return = $extCache->getVars();
		} else {
			$extCache->startDataCache();

			$iblockElement = new \CIBlockElement();
			$rsItems = $iblockElement->GetList(array(), array(
				'IBLOCK_ID' => self::IBLOCK_ID,
				'=CODE' => $code,
			), false, false, array('ID'));
			if ($item = $rsItems->Fetch())
			{
				$return = $item['ID'];
				$extCache->endDataCache($return);
			}
			else
				$extCache->abortDataCache();
		}

		return $return;
	}

	/**
	 * Возвращает карточку мероприятия по коду
	 * @param $code
	 * @param bool|false $refreshCache
	 * @return array|mixed
	 */
	public static function getByCode($code, $refreshCache = false)
	{
		$id = self::getIdByCode($code, $refreshCache);
		if ($id)
			return self::getById($id, $refreshCache);
		else
			return array();
	}

	/**
	 * Возвращает карточку мероприятия по коду или ID
	 * @param $code
	 * @return string
	 */
	public static function get($code)
	{
		if (is_numeric($code))
			return self::getById($code);
		else
			return self::getByCode($code);
	}

	/**
	 * Возвращает url карточки мероприятия
	 * @param $item
	 * @return string
	 */
	public static function getDetailUrl($item)
	{
		$hall = Hall::getById($item['HALL']);
		return $hall['DETAIL_PAGE_URL'] . ($item['CODE'] ? $item['CODE'] : $item['ID']) . '/';
	}

	/**
	 * Возвращает карточку мероприятия по ID
	 * @param int $id
	 * @param bool|false $refreshCache
	 * @return array|mixed
	 */
	public static function getById($id, $refreshCache = false)
	{
		$return = array();

		$id = intval($id);
		if (!$id)
			return $return;

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
				$id,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			static::CACHE_TIME
		);
		if(!$refreshCache && $extCache->initCache()) {
			$return = $extCache->getVars();
		} else {
			$extCache->startDataCache();

			$iblockElement = new \CIBlockElement();
			$filter = array(
				'IBLOCK_ID' => self::IBLOCK_ID,
				'ID' => $id,
			);
			$select = array(
				'ID', 'NAME', 'CODE', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT',
				'PROPERTY_PHOTOS',
			);
			$rsItems = $iblockElement->GetList(array(), $filter, false, false, $select);
			if ($item = $rsItems->GetNext())
			{
				$product = self::getSimpleById($item['ID']);

				$genre = Genre::getById($product['GENRE']);
				$detail =  self::getDetailUrl($product);
				$ipropValues = new ElementValues(self::IBLOCK_ID, $item['ID']);
				$iprop = $ipropValues->getValues();
				$title = $iprop['ELEMENT_META_TITLE'] ? $iprop['ELEMENT_META_TITLE'] :
					$item['NAME'] . ' - (здесь будет шаблон для заголовка)';
				$desc = $iprop['ELEMENT_META_DESCRIPTION'] ? $iprop['ELEMENT_META_DESCRIPTION'] :
					'Шаблон для описания ' . $item['NAME'] . '. (шаблон)';
				$pictures = array();
				$file = new \CFile();
				foreach ($item['PROPERTY_PHOTOS_VALUE'] as $picId)
					$pictures[] = $file->GetPath($picId);
				$runs = Run::getByEvent($item['ID']);
				$return = array(
					'ID' => $item['ID'],
					'NAME' => $item['NAME'],
					'TITLE' => $title,
					'DESCRIPTION' => $desc,
					'CODE' => $item['CODE'],
					'DETAIL_PAGE_URL' => $detail,
					'PREVIEW_PICTURE' => $file->GetPath($item['PREVIEW_PICTURE']),
					'PREVIEW_TEXT' => $item['~PREVIEW_TEXT'],
					'DETAIL_TEXT' => $item['~DETAIL_TEXT'],
					'GENRE' => $genre['NAME'],
					'PICTURES' => $pictures,
					'PRODUCT' => $product,
					'RUNS' => $runs,
				);

				$extCache->endDataCache($return);
			}
			else
				$extCache->abortDataCache();

		}

		return $return;
	}

	/**
	 * Увеличивает счетчики просмотров товара
	 * @param $productId
	 */
	public static function viewedCounters($productId)
	{
		\CIBlockElement::CounterInc($productId);
	}

	/**
	 * Формирует поисковый контент для мероприятия
	 * (добавляет жанр в заголовок и флаги в тело)
	 * @param $arFields
	 * @return mixed
	 */
	public static function beforeSearchIndex($arFields)
	{
		$productId = intval($arFields['ITEM_ID']);
		if ($productId && array_key_exists('BODY', $arFields))
		{
			$product = self::getSimpleById($productId);
			if ($product)
			{
				// жанр в заголовок
				$genre = Genre::getById($product['GENRE']);
				$arFields['TITLE'] .= ' ' . $genre['NAME'];

				// Флаги в тело
				$flags = Flags::getAll();
				foreach ($flags as $group)
					foreach ($group as $item)
						if ($product[$item['CODE']])
							$arFields['BODY'] .= ' ' . $item['NAME'];
			}
		}

		return $arFields;
	}

	/**
	 * Очищает кеш каталога
	 */
	public static function clearCatalogCache()
	{
		$phpCache = new \CPHPCache();
		$phpCache->CleanDir(static::CACHE_PATH . 'getAll');
		$phpCache->CleanDir(static::CACHE_PATH . 'getDataByFilter');
		$phpCache->CleanDir(static::CACHE_PATH . 'get');
		$phpCache->CleanDir(static::CACHE_PATH . 'getById');
	}

}

