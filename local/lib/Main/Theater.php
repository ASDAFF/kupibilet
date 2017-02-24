<?
namespace Local\Main;

use Local\System\ExtCache;

/**
 * Class Theater Театры
 * @package Local\Main
 */
class Theater
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Theater/';

	/**
	 * ID инфоблока
	 */
	const IBLOCK_ID = 1;

	const DIR = '/theater/';

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
			$rsItems = $iblockElement->GetList(array(), array(
				'IBLOCK_ID' => self::IBLOCK_ID,
			), false, false, array(
				'ID', 'NAME', 'CODE',
			));
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$return['ITEMS'][$id] = array(
					'ID' => $id,
					'NAME' => $item['NAME'],
				    'CODE' => $item['CODE'],
				    'DETAIL_PAGE_URL' => self::DIR . ($item['CODE'] ? $item['CODE'] : $item['ID']) . '/',
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

}
