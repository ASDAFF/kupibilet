<?
namespace Local\System;

use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;

/**
 * Class ExtCache Расширенное кеширование
 * Поддерживает статическое кеширование, тегированное кеширование, принудительный сброс кеша
 * @package Local
 * @author t.k <tim.kukom@gmail.com>, 2015 (Sergey Leshchenko, 2012)
 */
class ExtCache
{
	/**
	 * Время кеширования по-умолчанию
	 */
	const defaultCacheTime = 86400;

	/**
	 * @var null объект битриксовго класса CPHPCache
	 */
	private $phpCache = null;

	/**
	 * @var array Дополнительный идентификатор кеша
	 */
	private $customId = array();

	/**
	 * @var string Путь для сохранения кеша
	 */
	private $path = '/extcache';

	/**
	 * @var bool Использовать ли тегированное кеширование
	 */
	private $useTagCache = false;

	/**
	 * @var bool Запущено ли тегированное кеширование
	 */
	private $tagCacheStarted = false;

	/**
	 * @var array Теги для тегированного кеша
	 */
	private $tags = array();

	/**
	 * @var bool Влючен ли режим статического кеширования
	 */
	private $useStaticCache = true;

	/**
	 * @var bool Открыт ли участок кеширования
	 */
	private $started = false;

	/**
	 * @var int Время кеширования
	 */
	private $cacheTime = 0;

	/**
	 * @var string Тип кеширования
	 */
	private $cacheType = 'A';

	/**
	 * @var int Итоговое время кеширования (с учетом типа кеширования и настроек окружения)
	 */
	private $TTL = 0;

	/**
	 * @var string Итоговый идентификатор кеша (зависит от $customId + $path)
	 */
	private $cacheId = '';

	/**
	 * @var string Сущность для статического кеша (зависит только от $path)
	 */
	private $staticCacheEntity = '';

	/**
	 * @var bool Служебный флаг готовности объектов кеширования
	 */
	private $inited = false;

	/**
	 * @var bool Служебный флаг с результатом инициализации кеша
	 */
	private $initCacheReturn = false;

	/**
	 * @var bool Служебный флаг. Показывает, что старт кеша запущен без проверки готовности.
	 */
	private $startDataCacheBeforeInit = false;

	/**
	 * @var bool Служебный флаг
	 */
	private $cacheInited = false;

	/**
	 * @var int Ограничение количества ключей в статическом кеше
	 */
	private $staticCacheEntityLimit = 0;

	/**
	 * @var bool Добавлен ли путь кеширования в статический кеш
	 */
	private $isPathToStaticAdded = false;

	/**
	 * @var bool Режим для принудительного обновления кеша
	 * Пользователь должен обладать правами на сброс кеша:
	 * $GLOBALS["USER"]->CanDoOperation('cache_control')
	 */
	private $forceUpdate = true;

	/**
	 * Создает объект расширенного кеша
	 * @param array $customId ключ кеша
	 * @param string $path путь кеширования
	 * @param int $time время жизни
	 * @param array $tags теги, если указать false - то тегированный кеш не будет задействован
	 * @param bool|true $useStaticCache использовать ли статическое кеширование
	 * @param int $staticCacheLimit ограничение количества ключей в статическом кеше
	 * @param bool|true $forceUpdate режим для принудительного обновления кеша
	 */
	public function __construct(
		$customId = array(),
		$path = '',
		$time = 0,
		$tags = array(),
		$useStaticCache = true,
		$staticCacheLimit = 0,
		$forceUpdate = true)
	{
		$this->customId = $customId;
		$this->path = trim($path, "/ \t\n\r\0\x0B");
		$this->cacheTime = $time;
		// Тегированный кеш не используем, если $tags === false
		$this->useTagCache = $tags !== false;
		$this->addCacheTags($tags);
		$this->useStaticCache = $useStaticCache ? true : false;
		$this->staticCacheEntityLimit = intval($staticCacheLimit);
		$this->forceUpdate = $forceUpdate ? true : false;
	}

	/**
	 * Инициализация механизма кеширования
	 * Проверяет готовность кеша (сначала статического)
	 * @return bool
	 */
	public function initCache()
	{
		$this->cacheInited = true;
		// Если уже открыт кешируемый участок, то в статическом кеше проверять не будем
		if (!$this->startDataCacheBeforeInit)
		{
			// если выбран режим использования виртуального (статического) кэша,
			// то первым делом проверим, нет ли в нем готовых данных
			if ($this->isStaticCacheEnabled())
			{
				$key = $this->getCacheId();
				$entity = $this->getStaticCacheEntity();
				if (StaticCache::isCacheSet($key, $entity))
				{
					// данные уже есть, сообщаем об этом
					return true;
				}
			}
		}

		if (!$this->inited)
		{
			$this->inited = true;

			$this->TTL = $this->getCacheTime();

			$id = $this->getCacheId();
			$path = $this->getCachePath();
			$phpCache = $this->getCacheObject();
			$this->initCacheReturn = $phpCache->InitCache($this->TTL, $id, $path);
		}

		return $this->initCacheReturn;
	}

	/**
	 * Возвращает результат кеширования
	 * (С учетом статического кеширования)
	 * @return mixed
	 */
	public function getVars()
	{
		if ($this->isStaticCacheEnabled())
		{
			$key = $this->getCacheId();
			$entity = $this->getStaticCacheEntity();

			// В режиме статического кеша проверяем наличие данных в нем
			// и сразу возвращаем, если данные есть
			if (StaticCache::isCacheSet($key, $entity))
				return StaticCache::getValue($key, $entity);

			// Если данных в статическом кеше нет, то получаем из php кеша
			$phpCache = $this->getCacheObject();
			$return = $phpCache->GetVars();

			// И сохраняем в статику
			StaticCache::setValue($key, $return, $entity, $this->staticCacheEntityLimit);
			$this->addPathToStaticCacheEntity($entity);
		}
		else
		{
			$phpCache = $this->getCacheObject();
			$return = $phpCache->GetVars();
		}

		return $return;
	}

	/**
	 * Открывает кешируемый участок. Если участок открыт без проверки наличия кеша,
	 * то возможен принудительный сброс кеша ($this->forceUpdate)
	 * @return bool
	 */
	public function startDataCache()
	{
		// Предотвращает дублирование
		if ($this->started)
			return false;

		// На всякий случай проверим вызов без инициализации
		// (когда нужно обновить кеш)
		if (!$this->cacheInited)
		{
			$this->startDataCacheBeforeInit = true;
			$this->initCache();
		}

		$return = false;

		if ($this->TTL > 0)
		{
			$this->started = true;

			$phpCache = $this->getCacheObject();

			// В режиме принудительного обновления кеша устанавливаем соотв. флаг
			$prev = '';
			if ($this->forceUpdate && $this->startDataCacheBeforeInit)
			{
				Cache::setClearCache(true);
				$prev = $_SESSION["SESS_CLEAR_CACHE"];
				$_SESSION["SESS_CLEAR_CACHE"] = 'Y';
			}


			$return = $phpCache->StartDataCache();

			// Вернем бывшее состояние
			if ($this->forceUpdate && $this->startDataCacheBeforeInit)
			{
				Cache::setClearCache($_GET["clear_cache"] === 'Y');
				$_SESSION["SESS_CLEAR_CACHE"] = $prev;
			}

			if ($return)
			{
				// если разрешен тегированный кэш, то запустим его буферизацию
				if ($this->useTagCache)
				{
					$path = $this->getCachePath();
					if (strlen($path))
					{
						if (defined('BX_COMP_MANAGED_CACHE'))
						{
							$taggedCache = Application::getInstance()->getTaggedCache();
							$taggedCache->StartTagCache($path);
							$this->tagCacheStarted = true;
						}
					}
				}
			}
		}
		return $return;
	}

	/**
	 * Закрывает кэшируемый участок
	 * @param mixed $value Данные для сохранения
	 * @param array $tags Дополнительные ключи для тегированного кеша
	 * @return bool
	 */
	public function endDataCache($value, $tags = array())
	{
		if (!$this->started)
			return false;

		$this->started = false;

		// Регистрируем теги
		if ($this->tagCacheStarted)
		{
			$taggedCache = Application::getInstance()->getTaggedCache();

			if (!empty($tags))
				$this->addCacheTags($tags);

			$tags = $this->getCacheTags();
			if (!empty($tags))
			{
				foreach ($tags as $tag)
					$taggedCache->RegisterTag($tag);
			}
			$taggedCache->EndTagCache();

			$this->tagCacheStarted = false;
		}

		// Сохраняем в статику
		if ($this->isStaticCacheEnabled())
		{
			$key = $this->getCacheId();
			$entity = $this->getStaticCacheEntity();
			StaticCache::setValue($key, $value, $entity, $this->staticCacheEntityLimit);
			$this->addPathToStaticCacheEntity($entity);
		}

		$phpCache = $this->getCacheObject();
		$phpCache->EndDataCache($value);

		return true;
	}

	/**
	 * Прерывает кеширование
	 * @return bool
	 */
	public function abortDataCache()
	{
		if (!$this->started)
			return false;

		$this->started = false;

		if ($this->tagCacheStarted)
		{
			$taggedCache = Application::getInstance()->getTaggedCache();
			$taggedCache->AbortTagCache();
			$this->tagCacheStarted = false;
		}

		$phpCache = $this->getCacheObject();
		$phpCache->AbortDataCache();

		return true;
	}

	/**
	 * Возвращает объект класса CPHPCache
	 * @return \CPHPCache|null
	 */
	public function getCacheObject()
	{
		if (!$this->phpCache)
			$this->phpCache = new \CPHPCache();

		return $this->phpCache;
	}

	/**
	 * Включен ли режим статического кеширования
	 * @return bool
	 */
	public function isStaticCacheEnabled()
	{
		return $this->useStaticCache;
	}

	/**
	 * Возвращает путь кеширования
	 * @return string
	 */
	public function getCachePath()
	{
		return $this->path;
	}

	/**
	 * Возвращает время жизни кеша с учетом типа кеширования
	 * @return int
	 */
	public function getCacheTime()
	{
		$time = intval($this->cacheTime);
		$time = $time > 0 ? $time : static::defaultCacheTime;
		$type = $this->cacheType;
		if ($type == 'N' || ($type == 'A' && \COption::GetOptionString('main', 'component_cache_on', 'Y') == 'N'))
		{
			$time = 0;
		}
		return $time;
	}

	/**
	 * Устанавливает тип кеширования
	 * @param string $type
	 */
	public function setCacheType($type = 'A')
	{
		$this->cacheType = $type != 'Y' && $type != 'N' ? 'A' : $type;
	}

	/**
	 * Возвращает идентификатор кеша на основании пути и заданного пользователем ID
	 * @return string
	 */
	public function getCacheId()
	{
		if (!$this->cacheId)
		{
			$params = array(
				$this->getCachePath(),
				$this->customId,
			);
			$this->cacheId = md5(serialize($params));
		}
		return $this->cacheId;
	}

	/**
	 * Возвращает идентификатор сущности для сохранения в статический кеш
	 * @return string
	 */
	public function getStaticCacheEntity()
	{
		if (!$this->staticCacheEntity)
		{
			$params = array(
				$this->getCachePath()
			);
			$this->staticCacheEntity = md5(serialize($params));
		}
		return $this->staticCacheEntity;
	}

	/**
	 * Добавляет теги для тегированного кеша
	 * @param array $tags
	 */
	public function addCacheTags($tags = array())
	{
		if (is_array($tags) && !empty($tags))
		{
			$tags = array_unique($tags);
			$this->tags = array_merge($tags, $this->tags);
		}
	}

	/**
	 * Возвращает теги для тегированного кеша
	 * @return array
	 */
	public function getCacheTags()
	{
		return $this->tags;
	}

	/**
	 * Добавляет путь кеширования для сущности статического кеширования
	 * @param string $entity
	 */
	private function addPathToStaticCacheEntity($entity = '')
	{
		if (!$this->isPathToStaticAdded)
		{
			$entity = $entity ? $entity : $this->getStaticCacheEntity();
			StaticCache::addEntityPath($this->getCachePath(), $entity);
			$this->isPathToStaticAdded = true;
		}
	}

	/**
	 * Сбрасывает кеш по текущему ключу
	 */
	public function clean()
	{
		$cacheId = $this->getCacheId();
		$path = $this->getCachePath();
		$phpCache = $this->getCacheObject();
		$phpCache->Clean($cacheId, $path);
	}

	/**
	 * Сбрасывает весь кеш для текущего пути
	 */
	public function cleanDir()
	{
		$path = $this->getCachePath();
		$phpCache = $this->getCacheObject();
		$phpCache->CleanDir($path);
	}
}
