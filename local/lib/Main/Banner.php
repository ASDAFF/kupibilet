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

            $iblockElement = new \CIBlockElement();
            $rsItems = $iblockElement->GetList(array("NAME" => "ASC"), array(
                'IBLOCK_ID' => self::IBLOCK_ID,
            ), false, false, array(
                'ID', 'NAME', 'PREVIEW_PICTURE',
                'PROPERTY_EVENT',
            ));
            while ($item = $rsItems->Fetch())
            {


                $id = intval($item['ID']);
                $return['ITEMS'][$id] = array(
                    'ID' => $id,
                    'NAME' => $item['NAME'],
                    'PREVIEW_PICTURE' => \CFile::GetPath($item['PREVIEW_PICTURE']),
                    'EVENT' => $item['PROPERTY_EVENT_VALUE'],
                );
                if ($item['CODE'])
                    $return['BY_CODE'][$item['CODE']] = $id;
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
