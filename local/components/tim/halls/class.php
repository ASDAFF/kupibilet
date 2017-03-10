<?
namespace Local\Main;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

class TimHalls extends \CBitrixComponent
{
	/**
	 * @var array Концертный зал
	 */
	public $hall = array();

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

		$hallCode = trim($urlDirs[2]);
		$eventCode = trim($urlDirs[3]);
		$runCodeDate = trim($urlDirs[4]);
		$runCodeTime = trim($urlDirs[5]);

		$template = 'halls';
		if ($hallCode)
		{
			$this->hall = Hall::get($hallCode);
			if ($this->hall)
			{
				$template = 'hall';
				$APPLICATION->AddChainItem($this->hall['NAME'], $this->hall['DETAIL_PAGE_URL']);
				if ($eventCode)
				{
					$this->event = Event::get($eventCode);
					if ($this->event)
					{
						$template = 'event';
						$APPLICATION->AddChainItem($this->event['NAME'], $this->event['DETAIL_PAGE_URL']);
						if ($runCodeDate && $runCodeTime)
						{
							$template = 'run';
							$this->run = Run::getByUrlCodes($this->event['RUNS'], $runCodeDate, $runCodeTime);
							if ($this->run)
							{
								$template = 'run';
							}
						}
					}
				}
			}
		}

		$this->IncludeComponentTemplate($template);
	}

}