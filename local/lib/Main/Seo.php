<?
namespace Local\Main;
use Local\System\ExtCache;

/**
 * Class Seo Seo-свойства для списочных страниц
 * @package Local\Main
 */
class Seo
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Seo/';

	/**
	 * ID инфоблока
	 */
	const IBLOCK_ID = 28;

	/**
	 * Возвращает все праздники
	 * @param string $url
	 * @param bool|false $refreshCache
	 * @return array
	 */
	public static function getByUrl($url, $refreshCache = false)
	{
		$return = array();

		$url = trim($url);

		$extCache = new ExtCache(
			array(
				__FUNCTION__,
				$url,
			),
			static::CACHE_PATH . __FUNCTION__ . '/',
			86400000
		);
		if(!$refreshCache && $extCache->initCache()) {
			$return = $extCache->getVars();
		} else {
			$extCache->startDataCache();

			$iblockElement = new \CIBlockElement();
			$rsItems = $iblockElement->GetList(array(), array(
				'IBLOCK_ID' => self::IBLOCK_ID,
				'ACTIVE' => 'Y',
			    '=NAME' => $url,
			), false, false, array(
				'ID', 'NAME', 'CODE',
			    'PROPERTY_CHILDREN',
			    'PROPERTY_TITLE',
			    'PROPERTY_DESCRIPTION',
			    'PROPERTY_H1',
			    'PROPERTY_TEXT',
			));
			if ($item = $rsItems->Fetch())
			{
				$return = array(
					'CHILDREN' => $item['PROPERTY_CHILDREN_VALUE'] == 1,
					'TITLE' => $item['PROPERTY_TITLE_VALUE'],
					'DESCRIPTION' => $item['PROPERTY_DESCRIPTION_VALUE'],
					'H1' => $item['PROPERTY_H1_VALUE'],
					'TEXT' => $item['PROPERTY_TEXT_VALUE']['TEXT'],
				);
			}

			$extCache->endDataCache($return);
		}

		return $return;
	}

}