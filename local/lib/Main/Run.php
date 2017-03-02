<?
namespace Local\Main;

use Local\System\ExtCache;

/**
 * Class Run Показы
 * @package Local\Main
 */
class Run
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Run/';

	/**
	 * ID инфоблока
	 */
	const IBLOCK_ID = 4;

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
				'ID',
				'PROPERTY_EVENT',
				'PROPERTY_DATE',
			));
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$eventId = intval($item['PROPERTY_EVENT_VALUE']);
				$return[$eventId][$id] = array(
					'ID' => $id,
				    'DATE' => $item['PROPERTY_DATE_VALUE'],
				    'TS' => MakeTimeStamp($item['PROPERTY_DATE_VALUE']),
				);
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	public static function getSimple($refreshCache = false)
	{
		$return = array();
		$items = self::getAll($refreshCache);
		foreach ($items as $eventId => $runs)
			foreach ($runs as $item)
				$return[$eventId][$item['ID']] = $item['TS'];

		return $return;
	}

	public static function getByEvent($eventId, $refreshCache = false)
	{
		$return = array();
		$eventId = intval($eventId);
		if (!$eventId)
			return $return;

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
				$eventId,
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
				'PROPERTY_EVENT' => $eventId,
			), false, false, array(
				'ID',
				'PROPERTY_DATE',
			));
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$return[$id] = array(
					'ID' => $id,
					'DATE' => $item['PROPERTY_DATE_VALUE'],
				);
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	public static function getById($id, $eventId, $refreshCache = false)
	{
		$items = self::getByEvent($eventId, $refreshCache);
		return $items[$id];
	}

}