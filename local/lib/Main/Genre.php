<?
namespace Local\Main;

use Local\System\ExtCache;

/**
 * Class Genre Жанры
 * @package Local\Main
 */
class Genre
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Genre/';

	/**
	 * ID инфоблока
	 */
	const IBLOCK_ID = 5;


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
			$rsItems = $iblockElement->GetList(array('SORT' => 'ASC'), array(
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
				);
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

	public static function getIdByCode($code, $refreshCache = false)
	{
		$items = self::getAll($refreshCache);
		return $items['BY_CODE'][$code];
	}

	public static function getByCode($code, $refreshCache = false)
	{
		$items = self::getAll($refreshCache);
		$id = $items['BY_CODE'][$code];
		return $items['ITEMS'][$id];
	}

	/**
	 * Возвращает группу для панели фильтров
	 * @return array
	 */
	public static function getGroup()
	{
		$return = array();

		$all = self::getAll();
		foreach ($all['ITEMS'] as $item)
			$return[$item['CODE']] = array(
				'ID' => $item['ID'],
				'CODE' => 'GENRE',
				'NAME' => $item['NAME'],
			);

		return $return;
	}

}
