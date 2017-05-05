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

	public static function getAll($refreshCache = false)
	{
		$return = [];

		$extCache = new ExtCache(
			[
				__FUNCTION__,
			],
			static::CACHE_PATH . __FUNCTION__ . '/',
			8640000
		);
		if (!$refreshCache && $extCache->initCache())
			$return = $extCache->getVars();
		else
		{
			$extCache->startDataCache();

			$iblockSection = new \CIBlockSection();
			$rsItems = $iblockSection->GetList(["NAME" => "ASC"], [
				'IBLOCK_ID' => Hall::IBLOCK_ID,
			], false, [
				'ID', 'NAME', 'CODE',
			]);
			while ($item = $rsItems->Fetch())
			{
				$id = intval($item['ID']);
				$return[$id] = [
					'ID' => $id,
					'NAME' => $item['NAME'],
				    'CODE' => $item['CODE'],
				];
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

	public static function getById($id, $refreshCache = false)
	{
		$items = self::getAll($refreshCache);

		return $items[$id];
	}

	public static function selectCity($id)
	{
		global $APPLICATION;
		$APPLICATION->set_cookie('SELECTED_CITY', $id, time()+60*60*24*365);
	}

	public static function getSelected()
	{
		global $APPLICATION;
		$cityID = $APPLICATION->get_cookie('SELECTED_CITY');
		if($cityID == ''){
			$cities = City::getAll();
			$city = array_shift($cities);
			$cityID = $city['ID'];
		}

		return $cityID;
	}

	public static function getGroup()
	{
		$return = array();

		$all = self::getAll();
		foreach ($all as $item)
			$return[$item['CODE']] = array(
				'ID' => $item['ID'],
				'CODE' => 'CITIES',
				'NAME' => $item['NAME'],
			);

		return $return;
	}

}
