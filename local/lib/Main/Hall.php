<?
namespace Local\Main;

use Local\System\ExtCache;

/**
 * Class Hall Концертный зал
 * @package Local\Main
 */
class Hall
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Hall/';

	/**
	 * ID инфоблока
	 */
	const IBLOCK_ID = 2;

	const DIR = '/halls/';

	public static function getAll($refreshCache = false)
	{
		$return = array();

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			8640000
		);
		if (!$refreshCache && $extCache->initCache())
			$return = $extCache->getVars();
		else
		{
			$extCache->startDataCache();

			$iblockElement = new \CIBlockElement();
			$rsItems = $iblockElement->GetList(array("NAME" => "ASC"), array(
				'IBLOCK_ID' => self::IBLOCK_ID,
			    'ACTIVE' => 'Y',
			), false, false, array(
				'ID', 'NAME', 'CODE', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'IBLOCK_SECTION_ID',
                'PROPERTY_SCHEME', 'PROPERTY_ADDRESS', 'PROPERTY_ZONE',
			));
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$cityId = intval($item['IBLOCK_SECTION_ID']);
				$city = City::getById($cityId);
				$return['ITEMS'][$id] = array(
					'ID' => $id,
					'NAME' => $item['NAME'],
				    'CODE' => $item['CODE'],
                    'DETAIL_TEXT' => $item['DETAIL_TEXT'],
                    'PICTURE' => $item['DETAIL_PICTURE'],
				    'DETAIL_PAGE_URL' => self::DIR . ($item['CODE'] ? $item['CODE'] : $item['ID']) . '/',
				    'SCHEME' => $item['PROPERTY_SCHEME_VALUE'],
                    'ADDRESS' => $item['PROPERTY_ADDRESS_VALUE'],
                    'ZONE' => $item['PROPERTY_ZONE_VALUE']['TEXT'],
				    'CITY' => $city['NAME'],
				);
				if ($item['CODE'])
					$return['BY_CODE'][$item['CODE']] = $id;
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	public static function getById($id, $refreshCache = false)
	{
		$items = self::getAll($refreshCache);
		return $items['ITEMS'][$id];
	}

	public static function getByCode($code, $refreshCache = false)
	{
		$items = self::getAll($refreshCache);
		$id = $items['BY_CODE'][$code];
		return $items['ITEMS'][$id];
	}

	public static function get($code, $refreshCache = false)
	{
		if (is_numeric($code))
			return self::getById($code, $refreshCache);
		else
			return self::getByCode($code, $refreshCache);
	}

	public static function updateScheme($ID, $scheme)
	{
		$iblockElement = new \CIBlockElement();
		$iblockElement->SetPropertyValuesEx($ID, self::IBLOCK_ID, array('SCHEME' => $scheme));
	}


}
