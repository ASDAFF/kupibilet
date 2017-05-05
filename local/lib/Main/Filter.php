<?
namespace Local\Main;

/**
 * Class Filter Панель фильтров, формирование свойств страницы, в зависимости от выбранных фильтров
 * @package Local\Main
 */
class Filter
{
	/**
	 * Путь для кеширования
	 */
	const CACHE_PATH = 'Local/Main/Filter/';

	/**
	 * Разделитель вариантов в URL
	 */
	const SEPARATOR = '-';

	/**
	 * @var array Полная структура панели фильтров
	 */
	private static $GROUPS = array();

	/**
	 * @var array Фильтры для выборки элементов
	 */
	private static $FILTER_BY_KEY = array();

	/**
	 * @var array Информация по элементам, выбранным по фильтрам
	 */
	private static $DATA_BY_KEY = array();

	/**
	 * @var string Ключ фильтра
	 */
	private static $PRODUCTS_KEY = array();

	/**
	 * @var string Раздел каталога
	 */
	public static $CATALOG_PATH = '/event/';

	/**
	 * Возвращает данные для построения панели фильтров, хлебные крошки и ID отфильтрованных элементов
	 * @param array $searchIds элементы, отфильтрованные поисковым запросом
	 * @param string $searchQuery
	 * @param array $urlParams
	 * @return array
	 */
	public static function getData($searchIds = array(), $searchQuery = '', $urlParams = array())
	{
		// Получаем все свойства для фильтров в сгруппированном виде
		self::$GROUPS = self::getGroups();
		// Помечаем выбранные пользователем варианты
		$cnt = self::setChecked($urlParams);
		// Формируем фильтры для каждого свойства, чтобы отсеять варианты с учетом пользовательских фильтров
		self::getUserFilter($searchIds);
		// Получаем элементы для всех фильтров
		self::getProductsByFilters();
		// Скрываем варианты, которые не попали в пользовательский фильтр
		self::hideVars();

		$data = self::$DATA_BY_KEY[self::$PRODUCTS_KEY];

		// Возвращаем данные в компонент
		return array(
			// Данные для построения панели
			'GROUPS' => self::$GROUPS,
			// Количество выбранных фильтров
			'CHECKED_CNT' => $cnt,
			// Базовый путь к каталогу
			'CATALOG_PATH' => self::$CATALOG_PATH,
			// Разделитель
			'SEPARATOR' => self::SEPARATOR,
			// Айдишники элементов
			'PRODUCTS_IDS' => $data['IDS'],
			// Хлебные крошки
			'BC' => self::getBreadCrumb($searchQuery),
			// Плашки выбранных фильтров
			'CUR_FILTERS' => self::getCurrentFilters($searchQuery),
			// Seo
			'SEO' => self::getSeoValues($searchQuery),
		    // текущий урл
		    'URL' => self::getUrlWithoutGroup($searchQuery),
		);
	}

	/**
	 * Возвращает все свойства, которые участвуют в фильтрации каталога
	 * @return array
	 */
	public static function getGroups()
	{
		$return = array();

		$return[] = array(
			'NAME' => 'Жанр',
			'TYPE' => 'genre',
		    'ITEMS' => Genre::getGroup(),
		);
		$flags = Flags::getAll();
		foreach ($flags as $name => $items)
			$return[] = array(
				'NAME' => $name,
				'ITEMS' => $items,
			);
		$return[] = array(
			'NAME' => 'Цена',
			'TYPE' => 'price',
		);
		$return[] = array(
			'NAME' => 'Дата',
			'TYPE' => 'date',
		);

		$return[] = array(
			'NAME' => 'Города',
			'TYPE' => 'cities',
			'ITEMS' => City::getGroup(),
		);

		return $return;
	}

	/**
	 * По текущему URL определяет какие из вариантов фильтров нажаты
	 * @param $urlParams
	 * @return int
	 */
	private static function setChecked($urlParams)
	{
		$url = urldecode($_SERVER['REQUEST_URI']);
		$urlDirs = explode('/', $url);

		$urlCodes = array();
		for ($i = 2; $i < count($urlDirs) - 1; $i++)
		{
			$parts = explode(self::SEPARATOR, $urlDirs[$i]);
			foreach ($parts as $part)
				$urlCodes[$part] = true;
		}


		$allCnt = 0;
		foreach (self::$GROUPS as &$group)
		{
			$cnt = 0;
			if ($group['TYPE'] == 'price')
			{
				if (isset($urlParams['p-from']))
				{
					$group['FROM'] = intval($urlParams['p-from']);
					$cnt++;
				}
				if (isset($urlParams['p-to']))
				{
					$group['TO'] = intval($urlParams['p-to']);
					$cnt++;
				}
			}
			elseif ($group['TYPE'] == 'date')
			{
				if (isset($urlParams['d']))
				{
					$group['DAY'] = $urlParams['d'];
					$cnt++;
				}
				else
				{
					if (isset($urlParams['d-from']))
					{
						$group['FROM'] = $urlParams['d-from'];
						$cnt++;
					}
					if (isset($urlParams['d-to']))
					{
						$group['TO'] = $urlParams['d-to'];
						$cnt++;
					}
				}
			}
			else
			{
				foreach ($group['ITEMS'] as $code => &$item)
				{
					if ($urlCodes[$code])
					{
						$item['CHECKED'] = true;
						$cnt++;
					}
				}
				unset($item);
			}

			if ($group['TYPE'] == 'cities' && !$cnt)
			{
				$selectedCityId = City::getSelected();
				foreach ($group['ITEMS'] as $code => &$item)
				{
					if ($item['ID'] == $selectedCityId)
					{
						$item['CHECKED'] = true;
						$cnt++;
					}
				}
			}

			$group['CHECKED_CNT'] = $cnt;
			$allCnt += $cnt;
		}
		unset($group);

		return $allCnt;
	}

	/**
	 * Формирует фильтры для каждого свойства, чтобы отсеять варианты с учетом пользовательских фильтров
	 * К примеру, пользователь выбрал жанр Драма и новинки
	 * В итоге для жанров у нас должен сформироваться фильтр только по новинкам, для новинок -
	 * только по Драме, а для всех остальных свойств - фильтр и по жанру и по новинкам
	 * @param array $searchIds
	 */
	public static function getUserFilter($searchIds = array())
	{
		// Коды свойств, участвующие в фильтрации
		// По ключу _PRODUCTS будет фильтр по всем свойствам, т.е. итоговый фильтр для элементов
		$codes = array(
			'_ALL' => '_ALL',
			'_PRODUCTS' => '_PRODUCTS',
		    'PRICE' => 'PRICE',
		    'DATE' => 'DATE',
		);
		foreach (self::$GROUPS as $group)
		{
			foreach ($group['ITEMS'] as $item)
				$codes[$item['CODE']] = $item['CODE'];
		}

		// Формируем фильтры для каждого свойства, некоторые могут оказаться одинаковыми
		$filters = array();
		foreach ($codes as $code)
		{
			$filters[$code] = array(
				'KEY' => '',
				'DATA' => array(),
			);

			if ($code == '_ALL')
				continue;

			if ($searchIds)
			{
				$filters[$code]['KEY'] = 'search';
				$filters[$code]['DATA']['ID'] = $searchIds;
			}

			foreach (self::$GROUPS as $group)
			{
				if ($group['TYPE'] == 'price')
				{
					if ('PRICE' == $code)
						continue;

					if (isset($group['FROM']))
					{
						$filters[$code]['DATA']['PRICE']['FROM'] = $group['FROM'];
						$filters[$code]['KEY'] .= '|f#' . $group['FROM'];
					}
					if (isset($group['TO']))
					{
						$filters[$code]['DATA']['PRICE']['TO'] = $group['TO'];
						$filters[$code]['KEY'] .= '|t#' . $group['TO'];
					}
				}
				elseif ($group['TYPE'] == 'date')
				{
					if ('DATE' == $code)
						continue;

					if (isset($group['DAY']))
					{
						$filters[$code]['DATA']['DATE']['DAY'] = MakeTimeStamp($group['DAY']);
						$filters[$code]['KEY'] .= '|dd#' . MakeTimeStamp($group['DAY']);
					}
					if (isset($group['FROM']))
					{
						$filters[$code]['DATA']['DATE']['FROM'] = MakeTimeStamp($group['FROM']);
						$filters[$code]['KEY'] .= '|df#' . MakeTimeStamp($group['FROM']);
					}
					if (isset($group['TO']))
					{
						$filters[$code]['DATA']['DATE']['TO'] = MakeTimeStamp($group['TO']);
						$filters[$code]['KEY'] .= '|dt#' . MakeTimeStamp($group['TO']);
					}
				}
				else
				{
					foreach ($group['ITEMS'] as $item)
					{
						if ($item['CODE'] == $code)
							continue;

						if ($item['CHECKED'])
						{
							if ($item['CODE'] == 'GENRE' || $item['CODE'] == 'CITIES')
							{
								$filters[$code]['DATA'][$item['CODE']][$item['ID']] = $item['ID'];
								$filters[$code]['KEY'] .= '|' . $item['ID'];
							}
							else
							{
								$filters[$code]['DATA'][$item['CODE']] = true;
								$filters[$code]['KEY'] .= '|' . $item['CODE'];
							}
						}
					}
				}
			}
		}

		self::$FILTER_BY_KEY = array();
		foreach ($codes as $code)
		{
			$key = $filters[$code]['KEY'];
			self::$FILTER_BY_KEY[$key] = $filters[$code]['DATA'];
		}

		// Теперь полученные фильтры добавим обратно в свойства
		foreach (self::$GROUPS as &$group)
		{
			if ($group['TYPE'] == 'price')
				$group['KEY'] = $filters['PRICE']['KEY'];
			elseif ($group['TYPE'] == 'date')
				$group['KEY'] = $filters['DATE']['KEY'];
			else
			{
				foreach ($group['ITEMS'] as &$item)
					$item['KEY'] = $filters[$item['CODE']]['KEY'];
				unset($item);
			}
		}
		unset($group);
		//debugmessage(self::$FILTER_BY_KEY);
		// Общий фильтр
		self::$PRODUCTS_KEY = $filters['_PRODUCTS']['KEY'];
	}

	/**
	 * Получаем данные для всех фильтров
	 */
	public static function getProductsByFilters()
	{
		self::$DATA_BY_KEY = array();
		foreach (self::$FILTER_BY_KEY as $key => $filter)
			self::$DATA_BY_KEY[$key] = Event::getDataByFilter($filter);

	}

	/**
	 * Помечаем скрытыми варианты свойств, которых нет среди товаров, отфильтрованных пользователем
	 * (выставляем количество товаров CNT, если оно = 0, то вариант считается скрытым)
	 */
	public static function hideVars()
	{
		foreach (self::$GROUPS as &$group)
		{
			$cntGroup = 0;

			if ($group['TYPE'] == 'price')
			{
				// Цены - для всех товаров
				$data = self::$DATA_BY_KEY[''];
				$group['MIN'] = floor($data['PRICE']['MIN'] / 100) * 100;
				$group['MAX'] = ceil($data['PRICE_TO']['MAX'] / 100) * 100;
				$cntGroup = $group['MIN'] == $group['MAX'] ? 0 : 1;
			}
			elseif ($group['TYPE'] == 'date')
			{
				// Даты - для всех товаров
				$data = self::$DATA_BY_KEY[''];
				$group['MIN'] = $data['DATE']['MIN'];
				$group['MAX'] = $data['DATE']['MAX'];
				$cntGroup = $group['CHECKED_CNT'];
			}
			else
			{
				foreach ($group['ITEMS'] as &$item)
				{
					$data = self::$DATA_BY_KEY[$item['KEY']];
					if ($item['CODE'] == 'GENRE' || $item['CODE'] == 'CITIES')
						$item['CNT'] = intval($data[$item['CODE']][$item['ID']]);
					else
						$item['CNT'] = intval($data[$item['CODE']]);

					$data = self::$DATA_BY_KEY[''];
					if ($item['CODE'] == 'GENRE' || $item['CODE'] == 'CITIES')
						$item['ALL_CNT'] = intval($data[$item['CODE']][$item['ID']]);
					else
						$item['ALL_CNT'] = intval($data[$item['CODE']]);

					$cntGroup += $item['CNT'];
				}
				unset($item);
			}

			$group['CNT'] = $cntGroup;
		}
		unset($group);
	}

	/**
	 * Формирует массив для добавления в хлебные крошки
	 * @param $searchQuery
	 * @return array
	 */
	private static function getBreadCrumb($searchQuery)
	{
		$href = self::$CATALOG_PATH;
		$return = array(
			array(
				'NAME' => 'Мероприятия',
				'HREF' => $href,
			),
		);

		if ($searchQuery)
			$return[] = array(
				'NAME' => 'Результаты поиска',
				'HREF' => $href . '?q=' . $searchQuery,
			);

		foreach (self::$GROUPS as $group)
		{
			if (!$group['CNT'])
				continue;

			if (!$group['BC'])
				continue;

			$cnt = 0;
			$singleCode = '';

			foreach ($group['ITEMS'] as $code => $item)
			{
				if ($item['CHECKED'] && $item['CNT'])
				{
					$singleCode = $code;
					$cnt++;
				}
			}

			if ($cnt == 1)
			{
				$item = $group['ITEMS'][$singleCode];
				$href .= $singleCode . '/';
				$return[] = array(
					'NAME' => $item['NAME'],
					'HREF' => $href,
				);
			}
		}

		return $return;
	}

	/**
	 * Возвращает url для быстрых плашек
	 * @param string $searchQuery
	 * @param bool $groupKey
	 * @return string
	 */
	private static function getUrlWithoutGroup($searchQuery = '', $groupKey = false)
	{
		$href = self::$CATALOG_PATH;
		$params = '';

		if ($searchQuery)
		{
			$params .= $params ? '&' : '?';
			$params .= 'q=' . $searchQuery;
		}

		foreach (self::$GROUPS as $key => $group)
		{
			if ($groupKey === $key)
				continue;

			if ($group['TYPE'] == 'price')
			{
				if (isset($group['FROM']) && $group['FROM'] > $group['MIN'])
				{
					$params .= $params ? '&' : '?';
					$params .= 'p-from=' . $group['FROM'];
				}
				if (isset($group['TO']) && $group['TO'] < $group['MAX'])
				{
					$params .= $params ? '&' : '?';
					$params .= 'p-to=' . $group['TO'];
				}
			}
			elseif ($group['TYPE'] == 'date')
			{
				if (isset($group['DAY']))
				{
					$params .= $params ? '&' : '?';
					$params .= 'd=' . $group['DAY'];
				}
				else
				{
					if (isset($group['FROM']))
					{
						$params .= $params ? '&' : '?';
						$params .= 'd-from=' . $group['FROM'];
					}
					if (isset($group['TO']))
					{
						$params .= $params ? '&' : '?';
						$params .= 'd-to=' . $group['TO'];
					}
				}
			}
			else
			{
				$part = '';
				foreach ($group['ITEMS'] as $code => $item)
				{
					if ($item['CHECKED'])
					{
						if ($part)
							$part .= self::SEPARATOR;
						$part .= $code;
					}
				}
				if ($part)
					$href .= $part . '/';
			}
		}

		return $href . $params;
	}

	/**
	 * Формирует массив для отображения плашек выбранных фильтров
	 * @param $searchQuery
	 * @return array
	 */
	private static function getCurrentFilters($searchQuery)
	{
		$return = array();

		if ($searchQuery)
		{
			$return[] = array(
				'NAME' => '<b>Поиск</b>: ' . $searchQuery,
				'HREF' => self::getUrlWithoutGroup(),
			);
		}

		foreach (self::$GROUPS as $key => $group)
		{
			if (!$group['CNT'])
				continue;
			if ($group['TYPE'] == 'cities')
				continue;

			$name = '';
			if ($group['TYPE'] == 'price')
			{
				if (isset($group['FROM']) && $group['FROM'] > $group['MIN'])
					$name = 'от ' . $group['FROM'];
				if (isset($group['TO']) && $group['TO'] < $group['MAX'])
					$name .= ' до ' . $group['TO'];
			}
			elseif ($group['TYPE'] == 'date')
			{
				if (isset($group['DAY']))
					$name = $group['DAY'];
				else
				{
					if (isset($group['FROM']))
						$name = 'с ' . $group['FROM'];
					if (isset($group['TO']))
						$name .= ' по ' . $group['TO'];
				}
			}
			else
			{
				foreach ($group['ITEMS'] as $item)
				{
					if ($item['CHECKED'])
					{
						if ($name)
							$name .= ', ';
						$name .= $item['NAME'];
					}
				}
			}
			if ($name)
			{
				$return[] = array(
					'NAME' => '<b>' . $group['NAME'] . '</b>: ' . $name,
					'HREF' => self::getUrlWithoutGroup($searchQuery, $key),
				);
			}
		}

		return $return;
	}

	/**
	 * Формирует данные для Seo
	 * @param $searchQuery
	 * @return array
	 */
	private static function getSeoValues($searchQuery)
	{
		if ($searchQuery)
		{
			return array(
				'H1' => 'Результаты поиска по запросу «' . $searchQuery . '»',
				'TITLE' => 'Результаты поиска по запросу «' . $searchQuery . '»',
			);
		}

		$type = '';
		$date = '';

		$href = self::$CATALOG_PATH;

		foreach (self::$GROUPS as $group)
		{
			if (!$group['CNT'])
				continue;

			$itemsCnt = 0;
			$lastItem = false;
			$part = '';
			$text = '';
			foreach ($group['ITEMS'] as $code => $item)
			{
				if ($item['CHECKED'] && $item['CNT'])
				{
					if ($part)
						$part .= self::SEPARATOR;
					$part .= $code;
					$itemsCnt++;
					$lastItem = $item;
					if ($text)
						$text .= ', ';
					$text .= strtolower($item['NAME']);
				}
			}
			if ($part)
				$href .= $part . '/';

			if ($group['TYPE'] == 'date')
			{
				$date = $group['DAY'];
			}

			if (!$itemsCnt)
				continue;

			if ($group['TYPE'] == 'genre')
			{
				$type = $text;
			}
		}

		if (!$type)
			$type = 'мероприятия';
		if (!$date)
			$date = date('Y') . ' год';


		$h1 = $type . ' ' . $date;
		$h1l = strtolower($h1);
		$title = 'Билеты на ' . $type . ' КМВ онлайн. Афиша на ' . $date . ' – KupiBilet Online';
		$description = 'Билеты на ' . $type .
			' в городах КМВ (Пятигорск, Кисловодск, Ессентуки) онлайн. Афиша мероприятий на ' . $date .
			'. Бронируйте билеты на сайте или по телефону +7 (928) 335-65-65.';
		$keywords = 'билеты на ' . $type . ' онлайн, купить билеты, афиша, ' . $date;
		$text = $h1l;

		return array(
			'H1' => $h1,
			'TITLE' => $title,
			'DESCRIPTION' => $description,
			'KEYWORDS' => $keywords,
			'TEXT' => $text,
		    'URL' => $href,
		);
	}

	/**
	 * Формирует ссылки для карты сайта - упрощенная версия
	 * (только один город, один профиль и т.п.)
	 * @return array
	 */
	public static function getSimpleSiteMap()
	{
		$return = array();
		/*$parts = array();

		$cities = City::getAll();

		$flags = Flags::getAll();
		$i = 0;
		foreach ($flags as $group)
		{
			$i++;
			$parts[$i][] = '';
			foreach ($group as $k => $f)
				$parts[$i][$k] = $f;
		}

		$parts['PROFILES'][] = '';
		$profiles = Profiles::getAll();
		foreach ($profiles['ITEMS'] as $item)
			$parts['PROFILES'][$item['ID']] = $item['CODE'];

		foreach ($parts['PROFILES'] as $pid => $profileCode)
		{
			foreach ($parts[4] as $fCode4 => $fProp4)
			{
				foreach ($parts[3] as $fCode3 => $fProp3)
				{
					foreach ($parts[2] as $fCode2 => $fProp2)
					{
						foreach ($parts[1] as $fCode1 => $fProp1)
						{
							foreach ($cities['ITEMS'] as $city)
							{
								$filter = array();
								$url = self::$CATALOG_PATH;

								$filter['CITY'][$city['ID']] = $city['ID'];
								$url .= $city['CODE'] . '/';
								if ($fCode1)
								{
									$filter[$fProp1['CODE']] = true;
									$url .= $fCode1 . '/';
								}
								if ($fCode2)
								{
									$filter[$fProp2['CODE']] = true;
									$url .= $fCode2 . '/';
								}
								if ($fCode3)
								{
									$filter[$fProp3['CODE']] = true;
									$url .= $fCode3 . '/';
								}
								if ($fCode4)
								{
									$filter[$fProp4['CODE']] = true;
									$url .= $fCode4 . '/';
								}
								if ($pid)
								{
									$filter['PROFILES'][$pid] = $pid;
									$url .= $profileCode . '/';
								}

								$ex = Sanatorium::ex3ByFilter($filter);

								if ($ex)
								{

									$name = $city['NAME'];
									$suffix = '';
									$prefix = '';

									$h1 = $prefix . $name . $suffix;

									$return[$url] = $h1;
								}
							}
						}
					}
				}
			}
		}*/

		return $return;
	}

	/**
	 * Формирует ссылки для карты сайта
	 * (перебирает разные фильтры и проверяет, есть ли товары для них)
	 * @return array
	 */
	public static function getSiteMap()
	{
		$return = array();

		return $return;
	}

	/**
	 * Возвращает ссылки на товары для карты сайта
	 * @return array
	 */
	public static function getSiteMapProducts()
	{
		$return = array();

		return $return;
	}

}