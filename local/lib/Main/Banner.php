<?
namespace Local\Main;

use Local\System\ExtCache;

/**
 * Class Banner Баннер
 * @package Local\Main
 */
class Banner
{
    /**
     * Путь для кеширования
     */
    const CACHE_PATH = 'Local/Main/Banner/';

    const IBLOCK_ID = 6;

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

	        $codeById = array();

	        $iblockSection = new \CIBlockSection();
	        $rsItems = $iblockSection->GetList(array(), array(
		        'IBLOCK_ID' => self::IBLOCK_ID,
		        'ACTIVE' => 'Y',
	        ), false, array(
		        'ID', 'NAME', 'CODE',
	        ));
	        while ($item = $rsItems->Fetch())
	        {
		        $code = trim($item['CODE']);
		        if (!$code)
			        continue;

		        $id = intval($item['ID']);
		        $codeById[$id] = $code;
		        $return[$code] = array();
	        }

            $iblockElement = new \CIBlockElement();
            $rsItems = $iblockElement->GetList(array('SORT' => 'ASC', 'NAME' => 'ASC'), array(
                'IBLOCK_ID' => self::IBLOCK_ID,
                'ACTIVE' => 'Y'
            ), false, false, array(
                'ID', 'NAME', 'PREVIEW_PICTURE', 'IBLOCK_SECTION_ID',
                'PROPERTY_EVENT',
                'PROPERTY_HREF',
            ));
            while ($item = $rsItems->Fetch())
            {
                $id = intval($item['ID']);
	            $section = intval($item['IBLOCK_SECTION_ID']);
	            $code = $codeById[$section];
                $return[$code][$id] = array(
                    'ID' => $id,
                    'NAME' => $item['NAME'],
                    'PREVIEW_PICTURE' => \CFile::GetPath($item['PREVIEW_PICTURE']),
                    'EVENT' => $item['PROPERTY_EVENT_VALUE'],
                    'HREF' => $item['PROPERTY_HREF_VALUE'],
                );
            }

            $extCache->endDataCache($return);
        }

        return $return;
    }

	/**
	 * Возвращает баннеры по коду раздела
	 * @param $code
	 * @param int $count
	 * @return array
	 */
	public static function getBySectionCode($code, $count = 0) {
		$all = self::getAll();
		$banners = $all[$code];
		if ($count)
			$banners = array_slice($banners, 0, $count);
		return $banners;
	}

}
