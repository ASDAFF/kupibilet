<?
namespace Local\System;

/**
 * Class StaticCache Статическое кеширование
 * Возможность задать лимит ключей для сущности
 * Возможность привязать пути к сущности для последующей очистки кеша по заданному пути
 * @package Local
 */
class StaticCache
{
	private static $CACHE = array();
	private static $entityByPath = array();

	/**
	 * Инициализация кеширования
	 * @param string $key
	 * @param string $entity
	 * @return bool true - если уже есть кеш по заданному ключу
	 */
	public static function init($key, $entity = '-')
	{
		$return = static::isCacheSet($key, $entity);
		if (!$return)
			static::setValue($key, '', $entity);

		return $return;
	}

	/**
	 * Проверяет наличие кеша по заданному ключу
	 * @param $key
	 * @param string $entity
	 * @return bool
	 */
	public static function isCacheSet($key, $entity = '-')
	{
		return isset(static::$CACHE[$entity][$key]);
	}

	/**
	 * Возвращает значение по ключу
	 * @param $key
	 * @param string $entity
	 * @return mixed
	 */
	public static function getValue($key, $entity = '-')
	{
		return static::$CACHE[$entity][$key];
	}

	/**
	 * Сохраняет значение в кеш
	 * @param $key
	 * @param $value
	 * @param string $entity
	 * @param int $limit
	 */
	public static function setValue($key, $value, $entity = '-', $limit = 0)
	{
		$limit = intval($limit);
		static::$CACHE[$entity][$key] = $value;
		if ($limit > 0 && count(static::$CACHE[$entity]) > $limit)
		{
			static::$CACHE[$entity] = array_slice(static::$CACHE[$entity], 1, null, true);
		}
	}

	/**
	 * Добавляет путь для сохранения кеша к сущности
	 * @param $path
	 * @param $entity
	 * @return bool
	 */
	public static function addEntityPath($path, $entity)
	{
		if (is_scalar($path) && is_scalar($entity))
		{
			static::$entityByPath[$path][] = $entity;
			return true;
		}
		return false;
	}

	/**
	 * Возвращает пути для сохранения кеша для сущности
	 * @param bool|false $path
	 * @return array
	 */
	public static function getEntityPath($path = false)
	{
		if ($path === false)
			return array_unique(static::$entityByPath);
		elseif (is_scalar($path) && isset(static::$entityByPath[$path]))
			return array_unique(static::$entityByPath[$path]);
		return array();
	}

	/**
	 * Очищает все сущности для заданного пути кеширования
	 * @param bool|false $path
	 */
	public static function clearEntityPath($path = false)
	{
		if ($path === false)
			static::$entityByPath = array();
		elseif (is_scalar($path))
			static::$entityByPath[$path] = array();
	}

	/**
	 * Сбрасывает кеш для заданного пути
	 * @param $path
	 * @return bool
	 */
	public static function flushByPath($path)
	{
		$return = false;
		if (is_scalar($path))
		{
			$entities = static::getEntityPath($path);
			if ($entities)
			{
				foreach ($entities as $entity)
					static::flushEntity($entity);
				$return = true;
			}
		}
		return $return;
	}

	/**
	 * Сбрасывает весь кеш
	 */
	public static function flushAll()
	{
		static::$CACHE = array();
	}

	/**
	 * Сбрасывает кеш для заданной сущности
	 * @param string $entity
	 */
	public static function flushEntity($entity = '-')
	{
		if (isset(static::$CACHE[$entity]))
			unset(static::$CACHE[$entity]);
	}

	/**
	 * Сбрасывает кеш по заданному ключу
	 * @param string $key
	 * @param string $entity
	 */
	public static function flush($key, $entity = '-')
	{
		if (isset(static::$CACHE[$entity][$key]))
			unset(static::$CACHE[$entity][$key]);
	}
}
