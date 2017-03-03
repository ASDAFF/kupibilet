<?
namespace Local\Main;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

class TimTheaters extends \CBitrixComponent
{
	/**
	 * @var array Театр
	 */
	public $theater = array();

	/**
	 * @var array Событие
	 */
	public $event = array();

	/**
	 * @var array Показ
	 */
	public $run = array();

	public function executeComponent()
	{
		global $APPLICATION;

		$url = urldecode($_SERVER['REQUEST_URI']);
		$urlDirs = explode('/', $url);

		$theaterCode = trim($urlDirs[2]);
		$eventCode = trim($urlDirs[3]);
		$runCode = trim($urlDirs[4]);

		/*$url = urldecode($_SERVER['REQUEST_URI']);
		$urlDirs = explode('/', $url);
		$code = $urlDirs[2];
		if ($code && count($urlDirs) > 3)
			if (is_numeric($code))
				$this->product = Event::getById($code);
			else
				$this->product = Event::getByCode($code);

		if ($this->product)
		{
			// Счетчик просмотренных
			Event::viewedCounters($this->product['ID']);
			$this->tabCode = $urlDirs[4];
		}*/

		$template = 'theaters';
		if ($theaterCode)
		{
			$this->theater = Hall::get($theaterCode);
			if ($this->theater)
			{
				$template = 'theater';
				$APPLICATION->AddChainItem($this->theater['NAME'], $this->theater['DETAIL_PAGE_URL']);
				if ($eventCode)
				{
					$this->event = Event::get($eventCode);
					if ($this->event)
					{
						$template = 'event';
						$APPLICATION->AddChainItem($this->event['NAME'], $this->event['DETAIL_PAGE_URL']);
					}
				}
			}
		}

		$this->IncludeComponentTemplate($template);
	}

}