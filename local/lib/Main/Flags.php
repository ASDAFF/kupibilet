<?
namespace Local\Main;

/**
 * Class Flags Простые свойства санаториев
 * @package Local\Main
 */
class Flags
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Flags/';

	private static $all = array(
		'Спецпредложения' => array(
			'new' => array(
				'CODE' => 'NEW',
				'NAME' => 'Новинка',
			    'MAP' => true,
			),
			'action' => array(
				'CODE' => 'ACTION',
				'NAME' => 'Акция',
				'MAP' => true,
			),
			'rate' => array(
				'CODE' => 'RATE',
				'NAME' => 'Без сервисного сбора',
				'MAP' => true,
			),
			'recommend' => array(
				'CODE' => 'RECOMMEND',
				'NAME' => 'Мы рекомендуем',
				'MAP' => true,
			),
		),
	);

	/**
	 * Возвращает все свойства
	 * @return array
	 */
	public static function getAll()
	{
		return self::$all;
	}

	/**
	 * Возвращает свойства в формате для селекта
	 * @return array
	 */
	public static function getForSelect()
	{
		$return = array();
		foreach (self::$all as $props)
		{
			foreach ($props as $prop)
				$return[] = 'PROPERTY_' . $prop['CODE'];
		}
		return $return;
	}

	/**
	 * Возвращает коды свойств
	 * @return array
	 */
	public static function getCodes()
	{
		$return = array();
		foreach (self::$all as $props)
		{
			foreach ($props as $prop)
				$return[] = $prop['CODE'];
		}
		return $return;
	}
}
