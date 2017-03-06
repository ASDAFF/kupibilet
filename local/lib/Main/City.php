<?
namespace Local\Main;

use Local\System\ExtCache;

/**
 * Class City Город
 * @package Local\Main
 */
class City
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/City/';

	public static function getAll($refreshCache = false) {
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

			$iblockSection = new \CIBlockSection();
			$rsItems = $iblockSection->GetList(array("NAME" => "ASC"), array(
				'IBLOCK_ID' => Hall::IBLOCK_ID,
			), false, array(
				'ID', 'NAME',
			));
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$return[$id] = array(
					'ID' => $id,
					'NAME' => $item['NAME'],
				);
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	public static function getById($id, $refreshCache = false) {
		$items = self::getAll($refreshCache);
		return $items[$id];
	}

}
