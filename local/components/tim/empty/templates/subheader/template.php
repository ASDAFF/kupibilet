<?
$cities = \Local\Main\City::getAll();
$selectedCity = \Local\Main\City::getSelected();
$data = \Local\Main\Event::getDataByFilter(array('CITY' => $selectedCity));

$events = \Local\Main\Event::getByFilter(
	array('DATE' => 'asc'),
	$data['IDS'],
	false
);

$eventsCount = count($events['ITEMS']);
$eventsText = \Local\System\Utils::cardinalNumberRus($eventsCount, ' мероприятий', ' мероприятие', ' мероприятия');
$eventsText = $eventsCount . $eventsText;
?>
<div class="engRow">
    <div class="elHeader-2 cssBg-red">
        <div class="engBox ">
            <div class="elHeader-search engBox-8 engPl">
                <form id="elSearch" action="/event/">
                    <input id="elSearch-pole" class="cssBorderRadius-left cssLeft" type="text" name="q"
                           placeholder="Поиск концертов, мероприятий ..." autocomplete="off"
                           value="<?= $_REQUEST['q'] ?>"/>
                    <button id="elSearch-btn" class="cssBorderRadius-right engBtn cssLeft">Найти</button>
                </form>
            </div>
            <div class="elHeader-btn engBox-4 engPl cssText-center">
                <div class="elCityList">
					<?
					$selectedCity = \Local\Main\City::getSelected();
					if ($selectedCity != '')
					{
						$firstCity = $cities[$selectedCity];
						unset($cities[$selectedCity]);
					}
					else
					{
						$firstCity = array_shift($cities);
					}
					?>
                    <div class="elCityList-title"><span id="elCityList-title"> <?= $firstCity['NAME'] ?> </span>
                        <svg class="engSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26">
                            <path d="M 6.4140625 7.5859375 L 3.5859375 10.414062 L 13 19.828125 L 22.414062 10.414062 L 19.585938 7.5859375 L 13 14.171875 L 6.4140625 7.5859375 z"></path>
                        </svg>
                        <div class="elCityList-list">
							<? foreach ($cities as $city)
							{
								?>
                                <span class="select-city" data-id="<?= $city['ID'] ?>"><?= $city['NAME'] ?></span>
							<? } ?>
                        </div>
                    </div>
                    <a href="/event/"><?= $eventsText ?></a>
                </div>
            </div>
        </div>
    </div>
</div>