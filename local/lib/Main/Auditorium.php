<?
namespace Local\Main;

use Local\System\ExtCache;

/**
 * Class Auditorium Залы
 * @package Local\Main
 */
class Auditorium
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Auditorium/';

	/**
	 * ID инфоблока
	 */
	const IBLOCK_ID = 2;

	public static function getByTheater($theaterId, $refreshCache = false)
	{
		$return = array();
		$theaterId = intval($theaterId);
		if (!$theaterId)
			return $return;

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
			    $theaterId,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			8640000
		);
		if (!$refreshCache && $extCache->initCache())
			$return = $extCache->getVars();
		else
		{
			$extCache->startDataCache();

			$return = array();

			$iblockElement = new \CIBlockElement();
			$rsItems = $iblockElement->GetList(array(), array(
				'IBLOCK_ID' => self::IBLOCK_ID,
				'PROPERTY_THEATER' => $theaterId,
			), false, false, array(
				'ID', 'NAME', 'CODE',
			));
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$return[$id] = array(
					'ID' => $id,
					'NAME' => $item['NAME'],
				    'CODE' => $item['CODE'],
				);
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	public static function getById($id, $theaterId, $refreshCache = false)
	{
		$items = self::getByTheater($theaterId, $refreshCache);
		return $items[$id];
	}

}
